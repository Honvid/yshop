<?php

namespace admin\controllers;

use Yii;
use common\models\MallGoodsType;

/**
 * Class 商品类型
 * @package admin\controllers
 */
class GoodsTypeController extends BaseController
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
     * 首页列表展示
     * @return mixed
     */
    public function actionList()
    {
        $filter = [
            'page' => $this->getParam('current'),
            'pageSize' => $this->getParam('rowCount'),
            'sort' => $this->getParam('sort'),
            'keys' => $this->getParam('searchPhrase')
        ];
        $this->jsonReturn(0, '成功', MallGoodsType::lists($filter));
    }

    /**
     * 添加商品类型
     * @return string
     */
    public function actionCreate()
    {
        return $this->render('view', [
            'title' => '添加类型',
        ]);
    }

    /**
     * 查看商品类型
     * @return string
     */
    public function actionView()
    {
        $id = $this->getParam('id');
        if(!empty($id)){
            $type = MallGoodsType::find_by_id($id);
        }

        return $this->render('view', [
            'type' => !empty($type) ? $type : [],
            'title' => !empty($type) ? '编辑类型' : '添加类型',
        ]);
    }

    /**
     * 商品类型更新处理
     * @return mixed
     */
    public function actionUpdate()
    {
        if(empty($this->getParam('cat_name'))){
            $this->jsonReturn(0, '请填写类型名称');
        }
        $group = Yii::$app->request->post('attr_group');
        $group = str_replace("\n", ',', str_replace("\r", '', $group));
        $data = [
            'cat_name' => $this->getParam('cat_name'),
            'enabled' => $this->getParam('enabled'),
            'attr_group' => $group,
        ];
        $result = MallGoodsType::update_type($data, $this->getParam('cat_id'));
        if($result > 0){
            $this->jsonReturn(1, '保存成功', ['id' => $result]);
        }
        $this->jsonReturn(0, '保存失败');
    }

    /**
     * 删除商品类型
     * @return mixed
     */
    public function actionDelete()
    {
        $id = $this->getParam('id');
        $result = MallGoodsType::delete_type(intval($id));
        if($result){
            $this->jsonReturn(1,'删除成功。');
        }
        $this->jsonReturn(0, '删除失败');
    }
}