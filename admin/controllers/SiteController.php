<?php
namespace admin\controllers;

use Yii;

/**
 * Class 后台首页
 * @package admin\controllers
 */
class SiteController extends BaseController
{
    /**
     * 默认首页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 错误处理
     * @return array|string
     */
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            switch($exception->statusCode){
                case 500:
                    return $this->render('500', ['exception' => $exception]);
                case 404:
                    return $this->render('404', ['exception' => $exception]);
                case 403:
                    return $this->render('403', ['exception' => $exception, 'url' => $_SERVER['HTTP_REFERER']]);
                default:
                    return $this->render('404', ['exception' => $exception]);
            }
        }
        return '';
    }
}