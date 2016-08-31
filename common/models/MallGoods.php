<?php

namespace common\models;

use Yii;
use common\models\MallGoodsCat;
use common\models\MallCategory;

/**
 * This is the model class for table "mall_goods".
 *
 * @property integer $goods_id
 * @property integer $cat_id
 * @property string $goods_sn
 * @property string $goods_name
 * @property string $goods_name_style
 * @property integer $click_count
 * @property integer $brand_id
 * @property string $provider_name
 * @property integer $goods_number
 * @property string $goods_weight
 * @property string $market_price
 * @property string $shop_price
 * @property string $promote_price
 * @property integer $promote_start_date
 * @property integer $promote_end_date
 * @property integer $warn_number
 * @property string $keywords
 * @property string $goods_brief
 * @property string $goods_desc
 * @property string $goods_thumb
 * @property string $goods_img
 * @property string $original_img
 * @property integer $is_real
 * @property string $extension_code
 * @property integer $is_on_sale
 * @property integer $is_alone_sale
 * @property integer $is_shipping
 * @property integer $integral
 * @property integer $add_time
 * @property integer $sort_order
 * @property integer $is_delete
 * @property integer $is_best
 * @property integer $is_new
 * @property integer $is_hot
 * @property integer $is_promote
 * @property integer $bonus_type_id
 * @property integer $last_update
 * @property integer $goods_type
 * @property string $seller_note
 * @property integer $give_integral
 * @property integer $rank_integral
 * @property integer $suppliers_id
 * @property integer $is_check
 */
class MallGoods extends BaseModel
{
    const SN_PREFIX = 'OTT_MALL';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'click_count', 'brand_id', 'goods_number', 'promote_start_date', 'promote_end_date', 'warn_number', 'is_real', 'is_on_sale', 'is_alone_sale', 'is_shipping', 'integral', 'add_time', 'sort_order', 'is_delete', 'is_best', 'is_new', 'is_hot', 'is_promote', 'bonus_type_id', 'last_update', 'goods_type', 'give_integral', 'rank_integral', 'suppliers_id', 'is_check'], 'integer'],
            [['goods_weight', 'market_price', 'shop_price', 'promote_price'], 'number'],
            [['goods_desc'], 'string'],
            [['goods_sn', 'goods_name_style'], 'string', 'max' => 60],
            [['goods_name'], 'string', 'max' => 120],
            [['provider_name'], 'string', 'max' => 100],
            [['keywords', 'goods_brief', 'goods_thumb', 'goods_img', 'original_img', 'seller_note'], 'string', 'max' => 255],
            [['extension_code'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => '商品的自增id',
            'cat_id' => '商品所属商品分类id，取值category的cat_id',
            'goods_sn' => '商品的唯一货号',
            'goods_name' => '商品的名称',
            'goods_name_style' => '商品名称显示的样式；包括颜色和字体样式；格式如#ff00ff+strong',
            'click_count' => '商品点击数',
            'brand_id' => '品牌id，取值于brand 的brand_id',
            'provider_name' => '供货人的名称，程序还没实现该功能',
            'goods_number' => '商品库存数量',
            'goods_weight' => '商品的重量，以克为单位',
            'market_price' => '市场售价',
            'shop_price' => '本店售价',
            'promote_price' => '促销价格',
            'promote_start_date' => '促销价格开始日期',
            'promote_end_date' => '促销价结束日期',
            'warn_number' => '商品报警数量',
            'keywords' => '商品关键字，放在商品页的关键字中，为搜索引擎收录用',
            'goods_brief' => '商品的简短描述',
            'goods_desc' => '商品的详细描述',
            'goods_thumb' => '商品在前台显示的微缩图片，如在分类筛选时显示的小图片',
            'goods_img' => '商品的实际大小图片，如进入该商品页时介绍商品属性所显示的大图片',
            'original_img' => '应该是上传的商品的原始图片',
            'is_real' => '是否是实物，1，是；0，否；比如虚拟卡就为0，不是实物',
            'extension_code' => '商品的扩展属性，比如像虚拟卡',
            'is_on_sale' => '该商品是否开放销售，1，是；0，否',
            'is_alone_sale' => '是否能单独销售，1，是；0，否；如果不能单独销售，则只能作为某商品的配件或者赠品销售',
            'integral' => '购买该商品可以使用的积分数量，估计应该是用积分代替金额消费；但程序好像还没有实现该功能',
            'add_time' => '商品的添加时间',
            'sort_order' => '应该是商品的显示顺序，不过该版程序中没实现该功能',
            'is_delete' => '商品是否已经删除，0，否；1，已删除',
            'is_best' => '是否是精品；0，否；1，是',
            'is_new' => '是否是新品；0，否；1，是',
            'is_hot' => '是否热销，0，否；1，是',
            'is_promote' => '是否特价促销；0，否；1，是',
            'bonus_type_id' => '购买该商品所能领到的红包类型',
            'last_update' => '最近一次更新商品配置的时间',
            'goods_type' => '商品所属类型id，取值表goods_type的cat_id',
            'seller_note' => '商品的商家备注，仅商家可见',
            'give_integral' => '购买该商品时每笔成功交易赠送的积分数量。',
            'is_shipping' => 'Is Shipping',
            'rank_integral' => '等级积分',
            'suppliers_id' => '供应商ID',
            'is_check' => 'Is Check',
        ];
    }

    /**
     * 列出符合条件的产品类别
     * @param  array  $filter [description]
     * @return [type]         [description]
     */
    public static function lists($filter = [])
    {
        $query = self::find();
        $pageSize = 20;
        $page = 1;
        if (!empty($filter)) {
            // 每页显示条数
            if (!empty($filter['pageSize'])) {
                $pageSize = $filter['pageSize'];
                unset($filter['pageSize']);
            }
            // 页码
            if (!empty($filter['page'])) {
                $page = $filter['page'];
                unset($filter['page']);
            }
            // 排序
            if (isset($filter['sort'])) {
                $sorts = array();
                foreach ($filter['sort'] as $key => $sort) {
                    $sorts[$key] = strtoupper($sort) == 'DESC' ? SORT_DESC : SORT_ASC;
                }
                $query->addOrderBy($sorts);
            } else {
                $sorts = array();
                $sorts['goods_id'] = SORT_DESC;
                $query->addOrderBy($sorts);
            }

            if (!empty($filter['keys'])) {
                $query->andWhere("goods_name LIKE '%{$filter['keys']}%'");
            }

            if (isset($filter['filter'])) {
                foreach ($filter['filter']['filters'] as $key => $_condition) {
                    if(isset($_condition['logic'])) {
                        continue;
                    }
                    $_condition['value'] = is_numeric($_condition['value']) || strtotime($_condition['value']) === false ? $_condition['value'] : date('Y-m-d H:i:s', strtotime($_condition['value']));
                    switch ($_condition['operator']) {
                        case 'eq':
                            $query->andWhere([$_condition['field'] => $_condition['value']]);
                            break;
                        case 'neq':
                            $query->andWhere("`{$_condition['field']}`!='{$_condition['value']}'");
                            break;
                        case 'gt':
                            $query->andWhere("`{$_condition['field']}`>'{$_condition['value']}'");
                            break;
                        case 'lt':
                            $query->andWhere("`{$_condition['field']}`<'{$_condition['value']}'");
                            break;
                        case 'gte':
                            $query->andWhere("`{$_condition['field']}`>='{$_condition['value']}'");
                            break;
                        case 'lte':
                            $query->andWhere("`{$_condition['field']}`<='{$_condition['value']}'");
                            break;
                        case 'contains':
                            $query->andWhere("`{$_condition['field']}` LIKE '%{$_condition['value']}%'");
                            break;
                        default:
                            # code...
                            break;
                    }
                }
            }
        }

        $totalSize = $query->count();
        $totalPage = ceil($totalSize / $pageSize);
        if ($totalPage < $page) {
            $page = 1;
        }
        $query->offset(($page - 1) * $pageSize);
        $query->limit($pageSize);
        $command = $query->createCommand();
        $sql = $command->getRawSql();
        $list = $command->queryAll();
        return [
            'current' => $page,
            'rowCount' => $pageSize,
            'total' => $totalSize,
            'rows' => $list,
        ];
    }

    /**
     * 创建或者更新产品信息
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function edit_goods($data, $goods_id = '')
    {
        if(!empty($goods_id)){
            $goods = self::find()->where([ 'goods_id' => intval($goods_id) ])->one();
            if(empty($goods)){
                $goods = new self();
                $data['add_time'] = time();
            }
        }else{
            $goods = new self();
            $data['add_time'] = time();
        }
        $data['last_update'] = time();
        $goods->load($data, '');
        if(!$goods->save()){
            return false;
        }else{
            return $goods->attributes['goods_id'];
        }
    }

    /**
     * 查询所有分类下面在售产品数目，包含一个产品多个分类
     * @return [type] [description]
     */
    public static function count_by_category()
    {
        $query = self::find();
        $count1 = $query->select('cat_id, COUNT(*) AS goods_num')
            ->where(['is_delete' => 0, 'is_on_sale' => 1])
            ->groupBy('cat_id')
            ->asArray()
            ->all();
        // 查询多个分类产品的商品数目
        $search = MallGoodsCat::find();
        $count2 = $search->select('gc.cat_id, COUNT(*) AS goods_num')
            ->where('g.goods_id = gc.goods_id AND g.is_delete = 0 AND g.is_on_sale = 1')
            ->from('mall_goods_cat AS gc, mall_goods AS g')
            ->groupBy('gc.cat_id')
            ->asArray()
            ->all();
        
        $result = [];
        foreach ($count1 as $key => $value) {
            $result[$value['cat_id']] = $value['goods_num'];
            foreach ($count2 as $k => $v) {
                if($v['cat_id'] == $value['cat_id']){
                    $result[$value['cat_id']] = $value['goods_num'] + $v['goods_num'];
                }
            }
        }
        return $result;
    }

    /**
     * 根据制定条件检索产品
     * @param  [type] $filter [description]
     * @return [type]         [description]
     */
    public static function search_goods($filter)
    {   
        $query = self::find();
        if(!empty($filter)){
            foreach ($filter as $key => $value) {
                if(!empty($value)){
                    switch ($key) {
                        case 'keyword':
                            $like = "goods_name LIKE :keyword OR goods_sn LIKE :keyword OR goods_id LIKE :keyword";
                            $query->andWhere($like)->addParams([':keyword' => '%'.$value.'%']);
                            break;
                        case 'cat_id':
                            $ids = MallCategory::get_all_ids($value);
                            $query->andWhere('cat_id IN (' . join(', ', $ids) . ')');
                            break;
                        default:
                            $query->andWhere([$key => $value]);
                            break;
                    }
                }
            }
        }
        return $query->limit(10)->asArray()->all();
    }

    /**
     * 查询一个产品的所有信息
     * @param  [type]  $id       [description]
     * @param  boolean $is_array [description]
     * @return [type]            [description]
     */
    public static function find_by_goods_id($id, $is_array = true)
    {
        $query = self::find()->where(['goods_id' => intval($id)]);
        if($is_array){
            $query->asArray();
        }
        return $query->one();
    }

    /**
     * 查询分类ID下所有产品信息
     * @param  [type]  $cat_id       [description]
     * @return [type]            [description]
     */
    public static function find_by_cat_id($cat_id)
    {
        $query = self::find()->where(['cat_id' => intval($cat_id)]);
        return $query->asArray()->all();
    }

    /**
     * 生成产品编码
     * @return [type] [description]
     */
    public static function create_sn()
    {
        return self::SN_PREFIX . time() . rand(100, 999);
    }

    /**
     * @param $cat_id
     * @param $target_id
     */
    public static function move_cat_ids($cat_id, $target_id)
    {
        $goods = self::find()->where(['cat_id' => intval($cat_id)])->all();
        if($goods == 0){
            return false;
        }
        $result = self::updateAll(['cat_id' => intval($target_id)], ['cat_id' => intval($cat_id)]);
        if($result > 0){
            return true;
        }
        return false;
    }
}
