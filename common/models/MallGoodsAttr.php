<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mall_goods_attr".
 *
 * @property integer $goods_attr_id
 * @property integer $goods_id
 * @property integer $attr_id
 * @property string $attr_value
 * @property string $attr_price
 */
class MallGoodsAttr extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_goods_attr';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'attr_id'], 'integer'],
            [['attr_value'], 'required'],
            [['attr_value'], 'string'],
            [['attr_price'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_attr_id' => 'Goods Attr ID',
            'goods_id' => 'Goods ID',
            'attr_id' => 'Attr ID',
            'attr_value' => 'Attr Value',
            'attr_price' => 'Attr Price',
        ];
    }

    /**
     * 保存商品属性
     * @param $attr_id_list
     * @param $attr_value_list
     * @param $attr_price_list
     */
    public static function update_attr($attr_id_list, $attr_value_list, $attr_price_list, $goods_id)
    {
        self::deleteAll(['goods_id' => intval($goods_id)]);
        foreach ($attr_id_list as $k => $attr_id){
            $data = [
                'goods_id' => $goods_id,
                'attr_id' => $attr_id,
                'attr_value' => $attr_value_list[$k],
                'attr_price' => $attr_price_list[$k],
            ];
            $query = new self();
            $query->load($data, '');
            $query->save();
        }
    }
}
