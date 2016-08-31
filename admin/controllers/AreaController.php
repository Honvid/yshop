<?php
/**
 * Created by PhpStorm.
 * User: Honvid
 * Date: 16/5/22
 * Time: 上午9:26
 */

namespace admin\controllers;

use Yii;
use common\models\MallArea;

/**
 * Class 区域管理
 * @package admin\controllers
 */
class AreaController extends BaseController
{
    /**
     * 区域首页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'show' => MallArea::$is_show,
        ]);
    }

    /**
     * 区域列表
     * @return string
     */
    public function actionList()
    {
        $data = MallArea::get_parent_list();
        foreach($data as &$value){
            $value['title'] = $value['name'];
            $value['isParent'] = true;
            $value['lazy'] = true;
        }
        $this->jsonReturn(1,'success', $data);
    }

    /**
     * 查询区域子级区域
     * @return string
     */
    public function actionChildren()
    {
        $data = MallArea::find_children($this->getParam('id'));
        foreach($data as &$value){
            $value['title'] = $value['name'];
            $value['isParent'] = true;
            $value['lazy'] = true;
        }
        echo json_encode($data);
    }

    /**
     * 添加区域
     * @return string
     */
    public function actionCreate()
    {
        return $this->render('view', [
            'title' => '添区域',
        ]);
    }

    /**
     * 查看区域
     * @return string
     */
    public function actionView()
    {
        $area = MallArea::get_by_id($this->getParam('id'));
        if(!empty($area)){
            if($area['parent_id'] != 0) {
                $parent = MallArea::get_by_id($area['parent_id']);
            }
        }
        return $this->render('view', [
            'title' => '编辑区域',
            'area' => $area,
            'parent' => isset($parent) ? $parent : [],
        ]);
    }

    /**
     * 删除区域
     * @return string
     */
    public function actionDelete()
    {
        $id = intval($this->getParam('id'));
        $result = MallArea::delete_by_id($id);
        if($result){
            $this->jsonReturn(1, '删除成功');
        }
        $this->jsonReturn(0, '删除失败或地区下有子节点.');
    }

    /**
     * 更新状态
     * @return string
     */
    public function actionShow()
    {
        $id = intval($this->getParam('id'));
        $status = intval($this->getParam('status'));
        $result = MallArea::change_show($id, $status);
        if($result){
            $this->jsonReturn(1, '设置成功');
        }
        $this->jsonReturn(0, '设置失败或');
    }
}