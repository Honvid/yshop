<?php
namespace admin\controllers;


use Yii;
use common\models\MallGoods;
use common\models\MallGoodsCat;
use common\models\MallGoodsType;
use common\models\MallGoodsGallery;
use common\models\MallCategory;
use common\models\MallLinkGoods;
use common\models\MallGroupGoods;
use common\models\MallGoodsArticle;
use common\models\MallBrand;
use common\models\MallSuppliers;
use common\models\MallUserRank;
use common\models\MallVolumePrice;
use common\models\MallMemberPrice;
use common\helpers\UploadImage;
use common\models\MallAttribute;
use common\models\MallGoodsAttr;

/**
 * Class 商品管理
 * @package admin\controllers
 */
class GoodsController extends BaseController
{
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'common\vendor\ueditor\UEditorAction',
            ]
        ];
    }
    /**
     * 商品列表展示
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取列表
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
        $this->jsonReturn(0, '成功', MallGoods::lists($filter));
    }

    /**
     * 添加商品
     * @return string
     */
    public function actionCreate()
    {
        $category_list = MallCategory::category_list();
        $brand_list = MallBrand::get_brand_list();
        $suppliers_list = MallSuppliers::get_suppliers_list();
        $rank_list = MallUserRank::get_rank_list();
        $type_list = MallGoodsType::goods_type_list();
        return $this->render('view', [
            'title'        => '添加商品',
            'category_list'  => $category_list,
            'brand_list'     => $brand_list,
            'suppliers_list' => $suppliers_list,
            'rank_list'      => $rank_list,
            'type_list'      => $type_list,
        ]);
    }

    /**
     * 编辑商品
     * @return string
     */
    public function actionView()
    {
        $id = $this->getParam('id');
        if(!empty($id)){
            $goods = MallGoods::find_by_goods_id($id);

            // 商品类型及属性
            if(!empty($goods['goods_type'])){
                $attr_html = MallAttribute::create_attr_html($goods['goods_type'], $id);
            }
            // 关联商品
            $relation = MallLinkGoods::find_linked_goods($id);
            if(!empty($relation)){
                $link_goods = '';
                foreach ($relation as $key => $value) {
                    $status = $value['is_double'];
                    $link_id = $value['link_goods_id'].'_'.$status;
                    $link_goods .= '<option value="'.$link_id.'">'.$value['goods_name'].' -- '.MallLinkGoods::$status[$status]."</option>\n";
                }
            }
            // 商品配件
            $groups = MallGroupGoods::find_group_goods($id);
            if(!empty($groups)){
                $groups_goods = '';
                foreach ($groups as $key => $value) {
                    $val = $value['goods_id'] . '_' . $value['goods_price'];
                    $groups_goods .= '<option price="'.$value['shop_price'].'" value="'.$val.'" new="'.$value['goods_price'].'">'.$value['goods_name'].' -- ['.$value['goods_price']."]</option>\n";
                }
            }
            // 关联文章
            $articles = MallGoodsArticle::find_link_articles($id);
            if(!empty($articles)){
                $link_articles = '';
                foreach ($articles as $key => $value) {
                    $link_articles .= '<option value="'.$value['article_id'].'">'.$value['title']."</option>\n";
                }
            }
            // 商品图册
            $gallery = MallGoodsGallery::find_by_goods_id($id);
            // 商品扩展分类
            $cats = MallGoodsCat::find_linked_cats($id);
            // 商品会员价格
            $rank_price = MallMemberPrice::find_rank_price($id);
            if(!empty($rank_price)){
                foreach($rank_price as $k => $price){
                    $member_price[$price['user_rank']] = $price['user_price'];
                }
            }
            // 商品批发价格
            $volume_price = MallVolumePrice::find_volume_price($id);
        }
        $category_list = MallCategory::category_list();
        $brand_list = MallBrand::get_brand_list();
        $suppliers_list = MallSuppliers::get_suppliers_list();
        $rank_list = MallUserRank::get_rank_list();
        $type_list = MallGoodsType::goods_type_list();
        return $this->render('view', [
                'title'        => '编辑商品',
                'category_list'  => $category_list,
                'brand_list'     => $brand_list,
                'suppliers_list' => $suppliers_list,
                'rank_list'      => $rank_list,
                'type_list'      => $type_list,
                'goods'          => isset($goods) ? $goods : [],
                'gallery'        => isset($gallery) ? $gallery : [],
                'link_goods'     => isset($link_goods) ? $link_goods : [],
                'groups_goods'   => isset($groups_goods) ? $groups_goods : [],
                'link_articles'  => isset($link_articles) ? $link_articles : [],
                'attr_html'      => isset($attr_html) ? $attr_html : '',
                'goods_cats'     => isset($cats) ? $cats : [],
                'rank_price'     => isset($member_price) ? $member_price : [],
                'volume_price'   => isset($volume_price) ? $volume_price : [],
            ]);
    }

    /**
     * 处理添加商品
     * @return mixed
     */
    public function actionUpdate()
    {
        if(empty($this->getParam('name'))){
            $this->jsonReturn(0, '请填写产品名称');
        }
        if(empty($this->getParam('goods_type'))){
            $this->jsonReturn(0, '请选择产品类型');
        }
        if(empty($this->getParam('cat_id'))){
            $this->jsonReturn(0, '请选择产品分类');
        }

        $data = [
            'goods_name' => $this->getParam('name'),
            'goods_sn' => !empty($this->getParam('sn')) ? $this->getParam('sn') : MallGoods::create_sn(),
            'cat_id' => $this->getParam('cat_id'),
            'goods_type' => $this->getParam('goods_type'),
            'brand_id' => $this->getParam('brand_id'),
            'suppliers_id' => $this->getParam('suppliers_id'),
            'shop_price' => !empty($this->getParam('shop_price')) ? $this->getParam('shop_price') : 0,
            'market_price' => !empty($this->getParam('market_price')) ? $this->getParam('market_price') : 0,
            'give_integral' => !empty($this->getParam('give_integral')) ? $this->getParam('give_integral') : -1,
            'rank_integral' => !empty($this->getParam('rank_integral')) ? $this->getParam('rank_integral') : -1,
            'integral' => $this->getParam('integral'),
            'goods_desc' => base64_encode(Yii::$app->request->post('goods_desc')),
            'goods_weight' => $this->getParam('goods_weight') * $this->getParam('weight_unit'),
            'goods_number' => $this->getParam('goods_number'),
            'warn_number' => $this->getParam('warn_number'),
            'is_new' => !empty($this->getParam('is_new')) ? 1 : 0,
            'is_hot' => !empty($this->getParam('is_hot')) ? 1 : 0,
            'is_best' => !empty($this->getParam('is_best')) ? 1 : 0,
            'is_on_sale' => !empty($this->getParam('is_on_sale')) ? 1 : 0,
            'is_alone_sale' => !empty($this->getParam('is_alone_sale')) ? 1 : 0,
            'keywords' => $this->getParam('keywords'),
            'goods_brief' => $this->getParam('goods_brief'),
            'seller_note' => $this->getParam('seller_note'),
        ];

        // 促销
        if($this->getParam('is_promote') == 1){
            if(empty($this->getParam('promote_price'))){
                $this->jsonReturn(0, '请填写促销价格');
            }
            if(empty($this->getParam('promote_date'))){
                $this->jsonReturn(0, '请选择促销时间');
            }
            $promote_date = explode(' ', $this->getParam('promote_date'));
            $data['is_promote'] = $this->getParam('is_promote');
            $data['promote_price'] = $this->getParam('promote_price');
            $data['promote_start_date'] = strtotime($promote_date[0]);
            $data['promote_end_date'] = strtotime($promote_date[2]);
        }else{
            $data['is_promote'] = 0;
        }

        // 商品图片
        if($this->getParam('is_goods_img_web') == 0){
            $goods_img = UploadImage::upload('goods_img');
            if($goods_img !== false){
                $data['goods_img'] = $goods_img;
            }
        }else{
            $data['goods_img'] = $this->getParam['goods_img_url'];
        }
        // 缩略图
        if($this->getParam('is_goods_thumb_web') == 0){
            $goods_thumb = UploadImage::upload('goods_thumb');
            if($goods_thumb !== false){
                $data['goods_thumb'] = $goods_thumb;
            }
        }else{
            $data['goods_thumb'] = $this->getParam('goods_thumb_url');
        }

        $goods_id = MallGoods::edit_goods($data, $this->getParam('goods_id'));
        if($goods_id){
            // 会员价格
            MallMemberPrice::save_member_price($goods_id, $this->getParam('user_rank'), $this->getParam('user_price'));
            // 产品扩展分类
            MallGoodsCat::save_goods_cat($goods_id, $this->getParam('other_cat'));
            // 优惠价格
            MallVolumePrice::save_volume_price($goods_id, $this->getParam('volume_number'), $this->getParam('volume_price'));
            // 产品图册本地文件上传
            $file = UploadImage::upload('img_file');
            if($file !== false){
                MallGoodsGallery::save_gallery_list($goods_id, $file, $this->getParam('img_file_desc'));
            }
            // 产品图册网络连接地址
            if(!empty($this->getParam('img_url')) && !empty($this->getParam('img_url_desc'))){
                MallGoodsGallery::save_gallery_list($goods_id, $this->getParam('img_url'), $this->getParam('img_url_desc'));
            }
            // 处理关联商品
            MallLinkGoods::save_link_goods($goods_id, $this->getParam('relation'));
            // 处理关联配件
            MallGroupGoods::save_group_goods($goods_id, $this->getParam('parts'));
            // 处理关联文章
            MallGoodsArticle::save_goods_article($goods_id, $this->getParam('article'));


            $attr_id_list = $this->getParam('attr_id_list');
            $attr_value_list = $this->getParam('attr_value_list');
            $attr_price_list = $this->getParam('attr_price_list');

            MallGoodsAttr::update_attr($attr_id_list, $attr_value_list, $attr_price_list, $goods_id);
            $this->jsonReturn(1, '保存成功', ['goods_id' => $goods_id]);
        }else{
            $this->jsonReturn(0, '未知错误，请稍候重试。');
        }
    }

    /**
     * 查询产品
     * @return mixed
     */
    public function actionSearchGoods()
    {
        $filter = [
            'cat_id' => $this->getParam('cat_id'),
            'brand_id' => $this->getParam('brand_id'),
            'keyword' => $this->getParam('keyword'),
        ];
        $goods = MallGoods::search_goods($filter);
        if(!empty($goods)){
            $this->jsonReturn(1,'成功', $goods);
        }
        $this->jsonReturn(0, '失败');
    }

    /**
     * 删除产品图册
     * @return mixed
     */
    public function actionGallery()
    {
        $goods_id = $this->getParam('id');
        $img_id = $this->getParam('iid');
        if(!empty($goods_id) && !empty($img_id)){
            $res = MallGoodsGallery::delete_one(intval($goods_id), intval($img_id));
            if($res == 1){
                $this->jsonReturn(1, '删除成功', ['goods_id' => $goods_id]);
            }
        }
        $this->jsonReturn(0, '参数错误');
    }

    /**
     * 获取商品类型的属性项
     * @return mixed
     */
    public function actionType()
    {
        $cat_id = $this->getParam('id');
        $goods_id = $this->getParam('gid');
        $attr_html = MallAttribute::create_attr_html(intval($cat_id), intval($goods_id));
        $this->jsonReturn(1, 'Success', ['html' => $attr_html]);
    }
}