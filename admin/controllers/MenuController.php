<?php
namespace admin\controllers;

use common\helpers\RouteHelper;
use common\models\MallMenu;
use Yii;

/**
 * Class 菜单管理
 * @package admin\controllers
 */
class MenuController extends BaseController
{
    /**
     * 菜单首页
     * @return string
     */
    public function actionIndex()
    {
        $menu = MallMenu::get_menu_list();
        return $this->render('index', [
            'list' => $menu,
            'show' => MallMenu::$is_show,
        ]);
    }

    /**
     * 添加菜单
     * @return string
     */
    public function actionCreate()
    {
        $list = MallMenu::get_menu_list();
        return $this->render('view', [
            'title' => '添加菜单',
            'list' => $list,
            'route' => RouteHelper::route_list([ '后台' => 'admin', '前台' => 'home'])
        ]);
    }

    /**
     * 查看菜单
     * @return string
     */
    public function actionView()
    {
        $menu_id = $this->getParam('id');
        if(!empty($menu_id)){
            $menu = MallMenu::get_by_id($menu_id);
        }
        $list = MallMenu::get_menu_list();
        return $this->render('view', [
            'title' => '编辑菜单',
            'list' => $list,
            'route' => RouteHelper::route_list([ '后台' => 'admin', '前台' => 'home']),
            'menu' => isset($menu) ? $menu : [],
        ]);
    }

    /**
     * 保存菜单
     * @return mixed
     */
    public function actionUpdate()
    {
        $route = $this->getParam('menu_rule');
        if(!empty($route)){
            $route = implode(',', $route);
        }
        $data = [
            'menu_name' => $this->getParam('menu_name'),
            'desc' => $this->getParam('desc'),
            'menu_rule' => $route,
            'icon' => $this->getParam('icon'),
            'sort' => $this->getParam('sort'),
            'is_show' => $this->getParam('is_show'),
            'parent_id' => $this->getParam('parent_id'),
        ];
        $result = MallMenu::save_menu($data, $this->getParam('menu_id'));
        if($result){
            $this->jsonReturn(1, '保存成功', ['id' => $result]);
        }
        $this->jsonReturn(0, '保存失败');
    }

    /**
     * 删除菜单
     * @return mixed
     */
    public function actionDelete()
    {
        $result = MallMenu::delete_menu($this->getParam('id'));
        if($result){
            $this->jsonReturn(1, '删除成功');
        }
        $this->jsonReturn(0, '删除失败,请确认是否含有子节点');
    }
}