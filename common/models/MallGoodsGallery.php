<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mall_goods_gallery".
 *
 * @property integer $img_id
 * @property integer $goods_id
 * @property string $img_url
 * @property string $img_desc
 * @property string $thumb_url
 * @property string $img_original
 */
class MallGoodsGallery extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_goods_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['img_url', 'img_desc', 'thumb_url', 'img_original'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'img_id' => '商品相册自增id',
            'goods_id' => '图片属于商品的id',
            'img_url' => '实际图片url',
            'img_desc' => '图片说明信息',
            'thumb_url' => '微缩图片url',
            'img_original' => '根据名字猜，应该是上传的图片文件的最原始的文件的url',
        ];
    }

    /**
     * 保存某商品的相册图片
     * @param  [type] $goods_id  [description]
     * @param  [type] $file_list [description]
     * @param  [type] $file_desc [description]
     * @return [type]            [description]
     */
    public static function save_gallery_list($goods_id, $file_list, $file_desc)
    {
        if(!empty($file_list) && !empty($file_desc)){
            foreach ($file_list as $key => $file) {
                if(empty($file)){
                    break;
                }
                $data = [
                    'goods_id' => $goods_id,
                    'img_url' => $file,
                    'img_desc' => $file_desc[$key],
                    'thumb_url' => '',
                    'img_original' => $file,
                ];
                $query = new self();
                $query->load($data, '');
                $query->save();
            }
        }
        return [];
    }

    /**
     * 查询某商品的相册图片
     * @param  [type] $goods_id [description]
     * @return [type]           [description]
     */
    public static function find_by_goods_id($goods_id)
    {
        $query = self::find();
        $query->select('img_id, goods_id, img_url, img_desc, thumb_url');
        $query->where(['goods_id' => intval($goods_id)]);
        $query->orderBy('img_id');
        return $query->asArray()->all();
    }

    /**
     * 删除某商品的一张图片
     * @param  [type] $goods_id [description]
     * @param  [type] $img_id   [description]
     * @return [type]           [description]
     */
    public static function delete_one($goods_id, $img_id)
    {
        if(is_integer($goods_id) && is_integer($img_id)){
            return self::deleteAll(['img_id' => $img_id, 'goods_id' => $goods_id]);
        }
        return false;
    }
}
