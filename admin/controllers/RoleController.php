<?php
/**
 * Created by PhpStorm.
 * User: Honvid
 * Date: 16/5/10
 * Time: 上午11:16
 */

namespace admin\controllers;

use common\helpers\RouteHelper;
use common\models\MallRole;

/**
 * Class 角色管理
 * @package admin\controllers
 */
class RoleController extends BaseController
{
    /**
     * 首页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 列表展示
     * @return string
     */
    public function actionList()
    {
        $filter = [
            'page' => $this->getParam('current'),
            'pageSize' => $this->getParam('rowCount'),
            'sort' => $this->getParam('sort'),
            'keys' => $this->getParam('searchPhrase')
        ];
        return $this->jsonReturn(0, '成功', MallRole::lists($filter));
    }

    /**
     * 添加角色
     * @return string
     */
    public function actionCreate()
    {
        return $this->render('view', [
            'title' => '添加角色',
        ]);
    }

    /**
     * 查看角色
     * @return string
     */
    public function actionView()
    {
        $role_id = $this->getParam('id');
        if(!empty($role_id)){
            $role = MallRole::get_by_id($role_id);
        }
        return $this->render('view', [
            'title' => '编辑角色',
            'role' => isset($role) ? $role : [],
        ]);
    }

    /**
     * 配置权限
     * @return string
     */
    public function actionRights()
    {
        $role_id = $this->getParam('id');
        if(!empty($role_id)){
            $role = MallRole::get_by_id($role_id);
            return $this->render('rights',[
                'title' => '配置权限',
                'role'  => $role,
                'rights' => json_decode($role['rule_list'], true),
                'route' => RouteHelper::route_list_for_role([ '后台' => 'admin', '前台' => 'home']),
            ]);
        }
    }

    /**
     * 保存角色权限
     * @return bool
     */
    public function actionSave()
    {
        $role = MallRole::get_by_id($this->getParam('role_id'));
        if(empty($role)) return false;
        $rules = $this->getParam('route_list');
        $data = [
            'rule_list' => json_encode($rules),
        ];
        $result = MallRole::save_role($data, $this->getParam('role_id'));
        if($result){
            $this->jsonReturn(1, '保存成功', ['id' => $result]);
        }
        $this->jsonReturn(0, '保存失败');
    }

    /**
     * 保存角色
     * @return mixed
     */
    public function actionUpdate()
    {
        $data = [
            'role_name' => $this->getParam('role_name'),
            'desc' => $this->getParam('desc'),
            'sort' => $this->getParam('sort'),
        ];
        $result = MallRole::save_role($data, $this->getParam('role_id'));
        if($result){
            $this->jsonReturn(1, '保存成功', ['id' => $result]);
        }
        $this->jsonReturn(0, '保存失败');
    }

    /**
     * 删除角色
     * @return mixed
     */
    public function actionDelete()
    {
        $result = MallRole::delete_role($this->getParam('id'));
        if($result){
            $this->jsonReturn(1, '删除成功');
        }
        $this->jsonReturn(0, '删除失败');
    }
}