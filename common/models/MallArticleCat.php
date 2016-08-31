<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mall_article_cat".
 *
 * @property integer $cat_id
 * @property string $cat_name
 * @property integer $cat_type
 * @property string $keywords
 * @property string $cat_desc
 * @property integer $sort_order
 * @property integer $show_in_nav
 * @property integer $parent_id
 */
class MallArticleCat extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_article_cat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_type', 'sort_order', 'show_in_nav', 'parent_id'], 'integer'],
            [['cat_name', 'keywords', 'cat_desc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat_id' => '自增ID号',
            'cat_name' => '分类名称',
            'cat_type' => '分类类型；1，普通分类；2，系统分类；3，网店信息；4，帮助分类；5，网店帮助',
            'keywords' => '分类关键字',
            'cat_desc' => '分类说明文字',
            'sort_order' => '分类显示顺序',
            'show_in_nav' => '是否在导航栏显示；0，否；1，是',
            'parent_id' => '父节点id，取值于该表cat_id字段',
        ];
    }
}
