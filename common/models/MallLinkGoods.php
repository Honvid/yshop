<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mall_link_goods".
 *
 * @property integer $goods_id
 * @property integer $link_goods_id
 * @property integer $is_double
 * @property integer $admin_id
 */
class MallLinkGoods extends BaseModel
{
    public static $status = [
        0 => '单向关联',
        1 => '双向关联',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_link_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'link_goods_id', 'admin_id'], 'required'],
            [['goods_id', 'link_goods_id', 'is_double', 'admin_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => '商品ID',
            'link_goods_id' => '关联商品ID',
            'is_double' => '关联类型：0 单向关联， 1 双向关联',
            'admin_id' => '用户ID',
        ];
    }

    /**
     * 保存某商品的关联商品
     * @param  [type] $goods_id   [description]
     * @param  [type] $link_goods [description]
     * @return [type]             [description]
     */
    public static function save_link_goods($goods_id, $link_goods)
    {
        self::deleteAll(['goods_id' => $goods_id]);
        self::deleteAll(['link_goods_id' => $goods_id]);
        if(is_array($link_goods)){
            foreach ($link_goods as $key => $value) {
                $links = explode('_', $value);
                $data = [
                    'goods_id' => $goods_id, 
                    'link_goods_id' => $links[0], 
                    'is_double' => $links[1],
                    'admin_id' => 1,
                ];
                $goods = new self();
                $goods->load($data, '');
                if($goods->save()){
                    $data_link = [
                        'goods_id' => $links[0], 
                        'link_goods_id' => $goods_id, 
                        'is_double' => $links[1],
                        'admin_id' => 1,
                    ];
                    $link = new self();
                    $link->load($data_link, '');
                    $link->save();
                }
            }
        }
    }

    /**
     * 查出某商品的关联商品
     * @param  [type] $goods_id [description]
     * @return [type]           [description]
     */
    public static function find_linked_goods($goods_id)
    {
        $query = self::find();
        $query->select('g.goods_id, l.link_goods_id, g.goods_name, l.is_double, l.admin_id');
        $query->from('mall_link_goods as l');
        $query->leftJoin('mall_goods as g', 'g.goods_id=l.link_goods_id');
        $query->where(['l.goods_id' => intval($goods_id)]);
        $query->orderBy('g.goods_id');
        return $query->asArray()->all();
    }
}
