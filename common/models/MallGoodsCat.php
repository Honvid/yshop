<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mall_goods_cat".
 *
 * @property integer $goods_id
 * @property integer $cat_id
 */
class MallGoodsCat extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_goods_cat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'cat_id'], 'required'],
            [['goods_id', 'cat_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => '商品id',
            'cat_id' => '商品分类id',
        ];
    }

    /**
     * 保存某商品的扩展分类
     * @param  [type] $goods_id [description]
     * @param  [type] $cat_list [description]
     * @return [type]           [description]
     */
    public static function save_goods_cat($goods_id, $cat_list)
    {
        if(!empty($cat_list) && is_array($cat_list)){
            // 删除不再有的分类
            $cats = self::deleteAll(['goods_id' => $goods_id]);
            if($cats >= 0) {
                // 添加新的分类
                foreach ($cat_list as $key => $value) {
                    $data = [
                        'cat_id' => $value,
                        'goods_id' => $goods_id
                    ];
                    $cat = self::find()->where($data)->one();
                    if (empty($cat)) {
                        $cat = new self();
                    }
                    $cat->load($data, '');
                    $cat->save();
                }
            }
        }
    }

    /**
     * 获取商品的扩展分类
     * @param $goods_id
     */
    public static function find_linked_cats($goods_id)
    {
        return self::find()->where(['goods_id' => intval($goods_id)])->asArray()->all();
    }
}
