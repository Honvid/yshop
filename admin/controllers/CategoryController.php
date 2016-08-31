<?php

namespace admin\controllers;

use common\models\MallGoods;
use Yii;
use common\models\MallCategory;
use common\models\MallGoodsType;
use common\models\MallCatRecommend;
use common\models\MallAttribute;

/**
 * Class 分类管理
 * @package admin\controllers
 */
class CategoryController extends BaseController
{
    /**
     * 首页
     * @return string
     */
    public function actionIndex()
    {
        $list = MallCategory::category_list();
        return $this->render('index', [
            'list' => $list,
            'show' => MallCategory::$is_show,
        ]);
    }

    /**
     * 首页列表
     * @return string
     */
    public function actionCreate()
    {
        $type_list = MallGoodsType::goods_type_list();
        $category_list = MallCategory::category_list();
        return $this->render('view', [
            'category_list' => $category_list,
            'type_list' => !empty($type_list) ? $type_list : [],
            'title' => '添加分类',
        ]);
    }

    /**
     * 分类详情页
     * @return mixed
     */
    public function actionView()
    {
        $id = $this->getParam('id');
        if(!empty($id)){
            $category = MallCategory::find_by_id($id);
            if(!empty($category)){
                $recommend = MallCatRecommend::find_by_cat_id($id);
                if(!empty($recommend)){
                    $cat_recommend = [];
                    foreach($recommend as $data){
                        $cat_recommend[$data['recommend_type']] = 1;
                    }
                }
                if(!empty($category['filter_attr'])){
                    $filter_attr = explode(",", $category['filter_attr']);
                    foreach ($filter_attr as $key => $attr_id) {
                        $attr_info = MallAttribute::find_by_attr_id($attr_id);
                        $attr_list[] = [
                            'type_id' => $attr_info['cat_id'],
                            'attr_id' => $attr_id,
                            'attr_list' => MallAttribute::find_by_cat_id($attr_info['cat_id']),
                        ];
                    }
                }
            }
        }

        $type_list = MallGoodsType::goods_type_list();
        $category_list = MallCategory::category_list();
        return $this->render('view', [
            'category' => !empty($category) ? $category : [],
            'category_list' => $category_list,
            'type_list' => !empty($type_list) ? $type_list : [],
            'cat_recommend' => !empty($cat_recommend) ? $cat_recommend : [],
            'title' => '编辑分类',
            'attr_list' => !empty($attr_list) ? $attr_list : [],
        ]);
    }

    /**
     * 转移商品视图
     * @return string
     */
    public function actionMove()
    {
        $category_list = MallCategory::category_list();
        return $this->render('move', [
            'cat_id'        => $this->getParam('id'),
            'category_list' => $category_list,
        ]);
    }

    /**
     * 处理转移商品逻辑
     * @return mixed
     */
    public function actionMoved()
    {
        $cat_id = $this->getParam('cat_id');
        $target_cat_id = $this->getParam('target_cat_id');
        $result = MallGoods::move_cat_ids($cat_id, $target_cat_id);
        if($result){
            $this->jsonReturn(1, '转移成功.');
        }else{
            $this->jsonReturn(0, '转移失败.');
        }
    }

    /**
     * 分类更新处理
     * @return mixed
     */
    public function actionUpdate()
    {
        if(empty($this->getParam('cat_name'))){
            $this->jsonReturn(0, '请填写分类名称');
        }
        if(!empty($this->getParam('id'))){
            $check_name = MallCategory::find_by_name($this->getParam('cat_name'));
            if(!empty($check_name)){
                $this->jsonReturn(0, '该分类名称已存在');
            }
        }
        $filter = $this->getParam('filter_attr');
        if(!empty($filter)){
            $filter_attr = implode(',', $filter);
        }
        $data = [
            'cat_name' => $this->getParam('cat_name'),
            'keywords'   => $this->getParam('keywords'),
            'cat_desc' => Yii::$app->request->post('cat_desc'),
            'parent_id'   => $this->getParam('parent_id'),
            'sort_order' => $this->getParam('sort_order'),
            'template_file'    => $this->getParam('template_file'),
            'measure_unit'    => $this->getParam('measure_unit'),
            'show_in_nav'    => $this->getParam('show_in_nav'),
            'style'    => $this->getParam('style'),
            'is_show'    => $this->getParam('is_show'),
            'grade'    => $this->getParam('grade'),
            'filter_attr'    => isset($filter_attr) ? $filter_attr : '',
        ];

        $result = MallCategory::update_category($data, $this->getParam('cat_id'));
        if($result > 0){
            MallCatRecommend::update_cat_recommend($this->getParam('cat_recommend'), $result);
            $this->jsonReturn(1, '保存成功', ['id' => $result]);
        }
        $this->jsonReturn(0, '保存失败');
    }

    /**
     * 删除分类
     * @return mixed
     */
    public function actionDelete()
    {
        $id = $this->getParam('id');
        $result = MallCategory::delete_category(intval($id));
        if(!empty($result)){
            if(is_string($result)){
                $this->jsonReturn(2,$result);
            }else{
                $this->jsonReturn(1,'删除成功。');
            }
        }
        $this->jsonReturn(0, '删除失败');
    }

    /**
     * 查询分类属性
     * @return mixed
     */
    public function actionSearch()
    {
        $id = $this->getParam('cat_id');
        if(!empty($id)){
            $result = MallAttribute::find_by_cat_id($id);
            if(!empty($result)){
                $this->jsonReturn(1, 'Success', $result);
            }
        }
        $this->jsonReturn(0, 'Error');
    }
}
