<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mall_nav".
 *
 * @property integer $id
 * @property string $ctype
 * @property integer $cid
 * @property string $name
 * @property integer $ifshow
 * @property integer $vieworder
 * @property integer $opennew
 * @property string $url
 * @property string $type
 */
class MallNav extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_nav';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cid', 'ifshow', 'vieworder', 'opennew'], 'integer'],
            [['name', 'ifshow', 'vieworder', 'opennew', 'url', 'type'], 'required'],
            [['ctype', 'type'], 'string', 'max' => 10],
            [['name', 'url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ctype' => 'Ctype',
            'cid' => 'Cid',
            'name' => 'Name',
            'ifshow' => 'Ifshow',
            'vieworder' => 'Vieworder',
            'opennew' => 'Opennew',
            'url' => 'Url',
            'type' => 'Type',
        ];
    }

    /**
     * 通过分类ID删除
     * @param  [type] $cat_id [description]
     * @return [type]         [description]
     */
    public static function delete_by_cat_id($cat_id)
    {
        return self::deleteAll(['cid' => intval($cat_id), 'ctype' => 'c', 'type' => 'middle']);
    }
}
