<?php
/**
 * Created by PhpStorm.
 * User: Honvid
 * Date: 16/4/24
 * Time: 上午11:11
 */
namespace admin\controllers;

use Yii;
use common\models\MallRole;
use common\models\MallAdminUser;

/**
 * Class 系统管理
 * @package admin\controllers
 */
class ManagerController extends BaseController
{
    /**
     * 管理员首页
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
        return $this->jsonReturn(0, '成功', MallAdminUser::lists($filter));
    }

    /**
     * 添加管理员
     * @return string
     */
    public function actionCreate()
    {
        $list = MallRole::get_role_list();
        return $this->render('view', [
            'title' => '添加管理员',
            'list' => $list,
        ]);
    }

    /**
     * 管理员详情
     * @return string
     */
    public function actionView()
    {
        $list = MallRole::get_role_list();
        $user = MallAdminUser::get_by_id($this->getParam('id'));
        return $this->render('view', [
            'title' => '编辑管理员',
            'user' => $user,
            'list' => $list,
        ]);
    }

    /**
     * 更新管理员
     */
    public function actionUpdate()
    {
        $data = [
            'user_name' => $this->getParam('user_name'),
            'email' => $this->getParam('email'),
            'role' => $this->getParam('role'),
            'password' => $this->getParam('password'),
        ];
        $result = MallAdminUser::save_user($data, $this->getParam('user_id'));
        if($result){
            $this->jsonReturn(1, '保存成功', ['id' => $result]);
        }
        $this->jsonReturn(0, '保存失败');
    }

    /**
     * 删除管理员
     * @return mixed
     */
    public function actionDelete()
    {
        $result = MallAdminUser::delete_user($this->getParam('id'));
        if($result){
            $this->jsonReturn(1, '删除成功');
        }
        $this->jsonReturn(0, '删除失败');
    }
}