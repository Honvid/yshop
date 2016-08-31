<?php

namespace common\models;

use Yii;
use common\models\MallGoods;
/**
 * This is the model class for table "mall_group_goods".
 *
 * @property integer $parent_id
 * @property integer $goods_id
 * @property string $goods_price
 * @property integer $admin_id
 */
class MallGroupGoods extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_group_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'goods_id', 'admin_id'], 'required'],
            [['parent_id', 'goods_id', 'admin_id'], 'integer'],
            [['goods_price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent_id' => '商品ID',
            'goods_id' => '配件商品ID',
            'goods_price' => '配件价格',
            'admin_id' => '用户ID',
        ];
    }

    /**
     * 保存某商品的关联商品
     * @param  [type] $goods_id   [description]
     * @param  [type] $group_list [description]
     * @return [type]             [description]
     */
    public static function save_group_goods($goods_id, $group_list)
    {
        self::deleteAll(['parent_id' => $goods_id]);
        if(is_array($group_list)){
            foreach ($group_list as $key => $value) {
                $group = explode('_', $value);
                $data = [
                    'parent_id' => $goods_id,
                    'goods_id' => $group[0],
                    'goods_price' => (float)$group[1],
                    'admin_id' => 1,
                ];
                $group_goods = new self();
                $group_goods->load($data, '');
                $group_goods->save();
            }
        }
    }

    /**
     * 查询某商品的配件
     * @param  [type] $goods_id [description]
     * @return [type]           [description]
     */
    public static function find_group_goods($goods_id)
    {
            $groups = self::find()->select('goods_id, goods_price')->where(['parent_id' => intval($goods_id)])->asArray()->all();
            foreach ($groups as $key => &$value) {
                $good = MallGoods::find_by_goods_id($value['goods_id']);
                if(empty($good)){
                    self::deleteAll(['parent_id' => intval($goods_id), 'goods_id' => $value['goods_id']]);
                    unset($groups[$key]);
                    break;
                }
                $value['shop_price'] = $good['shop_price'];
                $value['goods_name'] = $good['goods_name'];
            }
            return $groups;
    }
}
