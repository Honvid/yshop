<?php

namespace admin\controllers;

use Yii;
use common\models\MallAttribute;
use common\models\MallGoodsType;

/**
 * Class 属性管理
 * @package admin\controllers
 */
class AttributeController extends BaseController
{
    /**
     * 首页
     * @return mixed
     */
    public function actionIndex()
    {
        $cat_id = $this->getParam('id');
        $type_list = MallGoodsType::goods_type_list();
        return $this->render('index', [
            'type_list' => $type_list,
            'id' => $cat_id,
        ]);
    }

    /**
     * 列表展示
     * @return mixed
     */
    public function actionList()
    {
        $filter = [
            'page' => $this->getParam('current'),
            'pageSize' => $this->getParam('rowCount'),
            'sort' => $this->getParam('sort'),
            'keys' => $this->getParam('searchPhrase'),
        ];
        $this->jsonReturn(0, '成功', MallAttribute::lists($this->getParam('id'), $filter));
    }

    /**
     * 添加属性
     * @return mixed
     */
    public function actionCreate()
    {
        $type_list = MallGoodsType::goods_type_list();
        return $this->render('view', [
            'type_list' => $type_list,
            'title' => '添加属性',
        ]);
    }

    /**
     * 查看属性
     * @return mixed
     */
    public function actionView()
    {
        $id = $this->getParam('id');
        if(!empty($id)){
            $attr = MallAttribute::find_by_attr_id($id);
            $group_list = MallGoodsType::get_attr_group_by_id($attr['cat_id']);
        }
        $type_list = MallGoodsType::goods_type_list();
        return $this->render('view', [
            'attr' => !empty($attr) ? $attr : [],
            'type_list' => $type_list,
            'group_list' => !empty($group_list) ? $group_list : [],
            'title' => '编辑属性',
        ]);
    }

    /**
     * 创建或者更新属性操作
     * @return mixed
     */
    public function actionUpdate()
    {
        if(empty($this->getParam('attr_name'))){
            $this->jsonReturn(0, '请填写属性名称');
        }
        $values = Yii::$app->request->post('attr_values');
        $values = str_replace("\n", ',', str_replace("\r", '', $values));
        $attr_group = $this->getParam('attr_group');
        $data = [
            'attr_name' => $this->getParam('attr_name'),
            'attr_values' => $values,
            'cat_id'   => $this->getParam('cat_id'),
            'attr_group' => $attr_group == null ? 0 : $attr_group,
            'attr_index'    => $this->getParam('attr_index'),
            'is_linked'    => $this->getParam('is_linked'),
            'attr_type'    => $this->getParam('attr_type'),
            'attr_input_type'    => $this->getParam('attr_input_type'),
        ];
        $result = MallAttribute::update_attribute($data, $this->getParam('attr_id'));
        if($result > 0){
            $this->jsonReturn(1, '保存成功', ['id' => $result]);
        }
        $this->jsonReturn(0, '保存失败');
    }

    /**
     * 删除属性
     * @return mixed
     */
    public function actionDelete()
    {
        $id = $this->getParam('id');
        $result = MallAttribute::delete_attribute(intval($id));
        if($result){
            $this->jsonReturn(1,'删除成功。');
        }
        $this->jsonReturn(0, '删除失败');
    }

    /**
     * 获取商品类型分组
     * @return mixed
     */
    public function actionGroup()
    {
        $id = $this->getParam('cat_id');
        $group = MallGoodsType::get_attr_group_by_id($id);
        $this->jsonReturn(1, 'Success', $group);
    }

}
