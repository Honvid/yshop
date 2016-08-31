<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mall_volume_price".
 *
 * @property integer $price_type
 * @property integer $goods_id
 * @property integer $volume_number
 * @property string $volume_price
 */
class MallVolumePrice extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_volume_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price_type', 'goods_id', 'volume_number'], 'required'],
            [['price_type', 'goods_id', 'volume_number'], 'integer'],
            [['volume_price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'price_type' => 'Price Type',
            'goods_id' => 'Goods ID',
            'volume_number' => 'Volume Number',
            'volume_price' => 'Volume Price',
        ];
    }

    /**
     * 保存该商品的优惠价格
     * @param  [type] $goods_id    [description]
     * @param  [type] $volume_list [description]
     * @return [type]              [description]
     */
    public static function save_volume_price($goods_id, $number_list, $price_list)
    {
        self::deleteAll(['goods_id' => intval($goods_id)]);
        foreach ($number_list as $key => $value) {
            if($value > 0 && $price_list[$key] > 0) {
                $data = [
                    'price_type' => 1,
                    'goods_id' => intval($goods_id),
                    'volume_number' => intval($value),
                    'volume_price' => (float)$price_list[$key],
                ];
                $query = new self();
                $query->load($data, '');
                $query->save();
            }
        }
    }

    /**
     * 查询某商品的批发价格
     * @param $goods_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function find_volume_price($goods_id)
    {
        return self::find()->where(['goods_id' => intval($goods_id)])->asArray()->all();
    }
}
