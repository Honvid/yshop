<?php
/**
 * Created by PhpStorm.
 * User: Honvid
 * Date: 16/5/12
 * Time: 下午3:53
 */

namespace admin\controllers;

use Yii;

/**
 * Class 供货商管理
 * @package admin\controllers
 */
class SuppliersController extends BaseController
{
    /**
     * 供货商首页
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
     * 添加供货商
     * @return string
     */
    public function actionCreate()
    {
        $list = MallRole::get_role_list();
        $suppliers = MallSuppliers::get_suppliers_list();
        return $this->render('view', [
            'title' => '添加管理员',
            'list' => $list,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * 供货商详情
     * @return string
     */
    public function actionView()
    {
        $list = MallRole::get_role_list();
        $user = MallAdminUser::get_by_id($this->getParam('id'));
        $suppliers = MallSuppliers::get_suppliers_list();
        return $this->render('view', [
            'title' => '编辑管理员',
            'user' => $user,
            'list' => $list,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * 更新供货商
     */
    public function actionUpdate()
    {

    }
}