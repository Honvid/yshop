<?php
/**
 * Created by PhpStorm.
 * User: Honvid
 * Date: 16/4/21
 * Time: 下午7:42
 */

namespace admin\controllers;

use common\models\MallAdminUser;
use Yii;

/**
 * Class 登陆管理
 * @package admin\controllers
 */
class UserController extends BaseController
{
    public $layout = 'content';
    protected $check_user_login_status = false;
    protected $check_user_oauth = false;

    /**
     * 登陆展示
     * @return string
     */
    public function actionLogin()
    {
        return $this->render('login');
    }

    /**
     * 登陆处理
     */
    public function actionDo()
    {
        $user_name = $this->getParam('name');
        $password = $this->getParam('pwd');
        $remember = $this->getParam('check');
        if(empty($user_name) || empty($password)){
            Yii::$app->end();
        }
        $oauth = MallAdminUser::check_login($user_name, $password, $remember);
        if($oauth){
            $this->redirect('/site/index', 302);
            Yii::$app->end();
        }
        Yii::$app->end();
    }
}