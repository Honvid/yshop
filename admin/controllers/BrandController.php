<?php

namespace admin\controllers;

use Yii;
use common\models\MallBrand;
use common\helpers\UploadImage;

/**
 * Class 品牌管理
 * @package admin\controllers
 */
class BrandController extends BaseController
{
    /**
     * 首页
     * @return mixed
     */
    public function actionIndex()
    {
        $list = MallBrand::get_brand_list();
        return $this->render('index', [
            'list' => $list,
        ]);
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
        return $this->jsonReturn(0, '成功', MallBrand::lists($filter));
    }

    /**
     * 创建品牌
     * @return mixed
     */
    public function actionCreate()
    {
        return $this->render('view', [
            'title' => '添加品牌',
        ]);
    }

    /**
     * 查看品牌详情
     * @return mixed
     */
    public function actionView()
    {
        $id = $this->getParam('id');
        if(!empty($id)){
            $brand = MallBrand::find_by_id($id);
        }

        return $this->render('view', [
            'brand' => !empty($brand) ? $brand : [],
            'title' => '编辑品牌',
        ]);
    }

    /**
     * 创建或者更新品牌
     * @return mixed
     */
    public function actionUpdate()
    {
        if(empty($this->getParam('brand_name'))){
            $this->jsonReturn(0, '请填写品牌名称');
        }
        $data = [
            'brand_name' => $this->getParam('brand_name'),
            'brand_desc' => Yii::$app->request->post('brand_desc'),
            'site_url'   => $this->getParam('site_url'),
            'sort_order' => $this->getParam('sort_order'),
            'is_show'    => $this->getParam('is_show'),
        ];

        $brand_logo = UploadImage::upload('brand_logo');
        if($brand_logo !== false){
            $data['brand_logo'] = $brand_logo;
        }
        $result = MallBrand::update_brand($data, $this->getParam('brand_id'));
        if($result > 0){
            $this->jsonReturn(1, '保存成功', ['id' => $result]);
        }
        $this->jsonReturn(0, '保存失败');
    }

    /**
     * 删除品牌
     * @return mixed
     */
    public function actionDelete()
    {
        $id = $this->getParam('id');
        $result = MallBrand::delete_brand(intval($id));
        if($result){
            $this->jsonReturn(1,'删除成功。');
        }
        $this->jsonReturn(0, '删除失败');
    }
}
