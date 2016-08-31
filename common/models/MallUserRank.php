<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_rank".
 *
 * @property integer $rank_id
 * @property string $rank_name
 * @property integer $min_points
 * @property integer $max_points
 * @property integer $discount
 * @property integer $show_price
 * @property integer $special_rank
 */
class MallUserRank extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_user_rank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rank_name'], 'required'],
            [['min_points', 'max_points', 'discount', 'show_price', 'special_rank'], 'integer'],
            [['rank_name'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rank_id' => '会员等级编号，其中0是非会员',
            'rank_name' => '会员等级名称',
            'min_points' => '该等级的最低积分',
            'max_points' => '该等级的最高积分',
            'discount' => '该会员等级的商品折扣',
            'show_price' => '是否在不是该等级会员购买页面显示该会员等级的折扣价格.1,显示;0,不显示',
            'special_rank' => '是否事特殊会员等级组.0,不是;1,是',
        ];
    }

    /**
     * 获得会员等级列表
     * @return [type] [description]
     */
    public static function get_rank_list()
    {
        return self::find()->orderBy('min_points')->asArray()->all();
    }
}
