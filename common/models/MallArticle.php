<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mall_article".
 *
 * @property integer $article_id
 * @property integer $cat_id
 * @property string $title
 * @property string $content
 * @property string $author
 * @property string $author_email
 * @property string $keywords
 * @property integer $article_type
 * @property integer $is_open
 * @property integer $add_time
 * @property string $file_url
 * @property integer $open_type
 * @property string $link
 * @property string $description
 */
class MallArticle extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'article_type', 'is_open', 'add_time', 'open_type'], 'integer'],
            [['content'], 'required'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 150],
            [['author'], 'string', 'max' => 30],
            [['author_email'], 'string', 'max' => 60],
            [['keywords', 'file_url', 'link', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => '自增ID号',
            'cat_id' => '该文章的分类，同ecs_article_cat的cat_id,如果不在，将自动成为保留类型而不能删除',
            'title' => '文章题目',
            'content' => '文章内容',
            'author' => '文章作者',
            'author_email' => '文章作者的email',
            'keywords' => '文章的关键字',
            'article_type' => '文章类型，0，普通；1，置顶；2和大于2的，为保留文章，保留文章不能删除',
            'is_open' => '是否显示。1，显示；0，不显示',
            'add_time' => '文章添加时间',
            'file_url' => '上传文件或者外部文件的url',
            'open_type' => '0,正常；当该字段为1或者2时，会在文章最后添加一个链接“相关下载”，连接地址等于file_url的值；但程序在此处有bug',
            'link' => '该文章标题所引用的连接，如果该项有值将不能显示文章内容，即该表中content的值',
            'description' => 'Description',
        ];
    }

    /**
     * 通过标题关键词搜索
     * @param  [type] $keywords [description]
     * @return [type]           [description]
     */
    public static function search_by_title($keywords)
    {
        $query = self::find();
        $query->select('article_id, title')->where('cat_id > 0');
        if(!empty($keywords)){
            $query->andWhere("title LIKE :keyword")->addParams([':keyword' => '%'.$keywords.'%']);
        }
        $query->orderBy('article_id DESC')->limit(50);
        return $query->asArray()->all();
    }

    /**
     * 检索一篇文章
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function find_by_id($id)
    {
        return self::find()->select('title')->where(['article_id' => intval($id)])->asArray()->one();
    }
}
