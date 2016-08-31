<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mall_member_price".
 *
 * @property integer $price_id
 * @property integer $goods_id
 * @property integer $user_rank
 * @property string $user_price
 */
class MallMemberPrice extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_member_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'user_rank'], 'integer'],
            [['user_price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'price_id' => 'Price ID',
            'goods_id' => 'Goods ID',
            'user_rank' => 'User Rank',
            'user_price' => 'User Price',
        ];
    }

    /**
     * 保存某商品的会员价格
     * @param  [type] $goods_id   [description]
     * @param  [type] $rank_list  [description]
     * @param  [type] $price_list [description]
     * @return [type]             [description]
     */
    public static function save_member_price($goods_id, $rank_list, $price_list)
    {
        foreach ($rank_list as $key => $value) {
            $data = [
                'goods_id' => $goods_id,
                'user_rank' => $value,
                'user_price' => $price_list[$key],
            ];
            $rank = self::find()->where(['goods_id' => $goods_id, 'user_rank' => $value])->one();
            if(!empty($rank)){
                if($rank->user_price < 0){
                    $rank->delete();
                }else{
                    $rank->load($data, '');
                    $rank->save();
                }
            }else{
                $rank = new self();
                $rank->load($data, '');
                $rank->save();
            }
        }
    }

    /**
     * 查询某商品的会员价格
     * @param $goods_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function find_rank_price($goods_id)
    {
        return self::find()->where(['goods_id' => intval($goods_id)])->asArray()->all();
    }
}
