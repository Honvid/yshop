<?php

namespace common\models;

use Yii;
use common\models\MallArticle;

/**
 * This is the model class for table "mall_goods_article".
 *
 * @property integer $goods_id
 * @property integer $article_id
 * @property integer $admin_id
 */
class MallGoodsArticle extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_goods_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'article_id', 'admin_id'], 'required'],
            [['goods_id', 'article_id', 'admin_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => '商品ID',
            'article_id' => '文章ID',
            'admin_id' => '用户ID',
        ];
    }

    /**
     * 保存某商品的关联文章
     * @param  [type] $goods_id     [description]
     * @param  [type] $article_list [description]
     * @return [type]               [description]
     */
    public static function save_goods_article($goods_id, $article_list)
    {
        self::deleteAll(['goods_id' => $goods_id]);
        if(is_array($article_list)){
            foreach ($article_list as $key => $value) {
                $data = [
                    'goods_id' => $goods_id,
                    'article_id' => $value,
                    'admin_id' => 1,
                ];
                $article = new self();
                $article->load($data, '');
                $article->save();
            }
        }
    }

    /**
     * 查询某商品的关联文章
     * @param  [type] $goods_id [description]
     * @return [type]           [description]
     */
    public static function find_link_articles($goods_id)
    {
        $articles = self::find()->select('article_id')->where(['goods_id' => intval($goods_id)])->asArray()->all();
        foreach ($articles as $key => &$value) {
            $article = MallArticle::find_by_id($value['article_id']);
            if(empty($article)){
                self::deleteAll(['goods_id' => intval($goods_id), 'article_id' => $value['article_id']]);
                unset($articles[$key]);
                break;
            }
            $value['title'] = $article['title'];
        }
        return $articles;
    }
}
