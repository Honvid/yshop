<?php

namespace admin\controllers;

use Yii;
use common\models\MallArticle;

/**
 * Class 文章管理
 * @package admin\controllers
 */
class ArticleController extends BaseController
{
    /**
     * 通过标题关键词检索50篇文章
     * @return [type] [description]
     */
    public function actionSearchByTitle()
    {
        $keywords = $this->getParam('keywords');
        $article = MallArticle::search_by_title($keywords);
        if(!empty($article)){
            $this->jsonReturn(1,'成功', $article);
        }
        return $this->jsonReturn(0, '失败');
    }
}