<?php
namespace admin\controllers;

use common\helpers\RedisKey;
use yii;
use yii\web\Controller;
use common\models\MallMenu;
use common\models\MallAdminUser;

/**
 * Class 后台公用
 * @package admin\controllers
 */
class BaseController extends Controller
{
    public $enableCsrfValidation = false; //  CSRF验证
    protected $check_user_login_status = true; // 是否登陆验证,默认需要验证登陆
    protected $check_user_oauth = true; // 是否进行权限验证,默认是
    protected $_actions = ['error', 'forbidden'];
    protected $_user_id = null;
    protected $_is_admin = 0;
    public $_menu = null;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);        
    }

    /**
     * 请求初始化
     */
    public function init()
    {

    }

    /**
     * Action执行前的初始化
     * @param yii\base\Action $action
     * @return bool
     * @throws yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $controllerID = $this->id;
        $actionID = $action->id;
        $module = $this->module->id;
        if($this->check_user_login_status) {
            $this->check_login_status();
            $this->display_menus($module);
        }
        if($this->check_user_oauth && !in_array($actionID, $this->_actions)){
            $this->check_user_oauth($module, $controllerID, $actionID);
        }
        return parent::beforeAction($action);
    }

    /**
     * 验证用户权限
     * @param $module
     * @param $controller
     * @param $action
     * @return bool
     */
    protected function check_user_oauth($module, $controller, $action)
    {
        if($this->_is_admin != 1) {
            $rights = MallAdminUser::user_rights($this->_user_id);
            $url = '/' . $module . '/' . $controller . '/' . $action;
            if (empty($rights) || !in_array($url, $rights)) {
                if (Yii::$app->request->isAjax) {
                    $this->jsonReturn(-1, '您没有此方法的权限.');
                } else {
                    throw new yii\web\ForbiddenHttpException;
                }
            }
        }
    }

    /**
     * 登陆验证
     */
    protected function check_login_status()
    {
        $session = Yii::$app->session;
        if(!$session->getIsActive()){
            $session->open();
        }

        if(!$session->has('admin_id') && !$session->has('admin_name')){
            $cookies = Yii::$app->request->cookies;
            if($cookies->has('admin_id') && $cookies->has('admin_pass')){
                $result = MallAdminUser::is_login($cookies->getValue('admin_id'), $cookies->getValue('admin_pass'));
                if(!$result){
                    $cookie = Yii::$app->response->cookies;
                    $cookie->remove('admin_id');
                    $cookie->remove('admin_pass');
                    $this->redirect('/user/login', 302);
                    Yii::$app->end();
                }else{
                    $this->_user_id = $cookies->getValue('admin_id');
                }
            }else{
                $this->redirect('/user/login', 302);
                Yii::$app->end();
            }
        }else{
            $this->_user_id = $session['admin_id'];
            $this->_is_admin = $session['is_admin'];
        }
    }

    /**
     * 筛选菜单
     * @param $module
     * @throws yii\web\ForbiddenHttpException
     */
    protected function display_menus($module)
    {
        $redis = Yii::$app->redis;
        $menus = $redis->get(RedisKey::ADMIN_MENU_LIST);
        $menus = json_decode($menus, true);
        if(empty($menus)) {
            $menus = MallMenu::get_menu_list();
        }
        $list = [];
        if($this->_is_admin == 1){
            foreach($menus as $menu){
                if($menu['is_show'] == 0) {
                    continue;
                }
                // 一级菜单
                if(strpos($menu['menu_rule'], ',') !== false){
                    $menu_route = explode(',', $menu['menu_rule']);
                    foreach($menu_route as $route){
                        $list[$menu['menu_id']] = [
                            'name' => $menu['menu_name'],
                            'url' => '#',
                            'icon' => $menu['icon'],
                            'active' => 0,
                        ];
                    }
                }else{
                    $url = substr($menu['menu_rule'], strlen($module) + 1);
                    if(empty($list[$menu['parent_id']])){
                        $list[$menu['menu_id']] = [
                            'name' => $menu['menu_name'],
                            'url' => $url,
                            'icon' => $menu['icon'],
                            'active' => strpos($url, $this->id .'/') !== false ? 1:0,
                        ];
                    }else{
                        if(strpos($url, $this->id .'/') !== false){
                            $list[$menu['parent_id']]['active'] = 1;
                        }
                        $list[$menu['parent_id']]['child'][] = [
                            'name' => $menu['menu_name'],
                            'url' => $url,
                            'icon' => $menu['icon'],
                            'active' => strpos($url, $this->id .'/') !== false ? 1:0,
                        ];
                    }
                }
            }
        }else {
            $rights = MallAdminUser::user_rights($this->_user_id);
            if (empty($rights)) {
                if (Yii::$app->request->isAjax) {
                    $this->jsonReturn(-1, '您没有相关权限.');
                } else {
                    throw new yii\web\ForbiddenHttpException;
                }
            }
            $list = [];
            foreach ($menus as $menu) {
                if ($menu['is_show'] == 0) {
                    continue;
                }
                // 一级菜单
                if (strpos($menu['menu_rule'], ',') !== false) {
                    $menu_route = explode(',', $menu['menu_rule']);
                    foreach ($menu_route as $route) {
                        if (in_array($route, $rights)) {
                            $list[$menu['menu_id']] = [
                                'name' => $menu['menu_name'],
                                'url' => '#',
                                'icon' => $menu['icon'],
                                'active' => 0,
                            ];
                        }
                    }
                } else {
                    if (in_array($menu['menu_rule'], $rights)) {
                        $url = substr($menu['menu_rule'], strlen($module) + 1);
                        if (empty($list[$menu['parent_id']])) {
                            $list[$menu['menu_id']] = [
                                'name' => $menu['menu_name'],
                                'url' => $url,
                                'icon' => $menu['icon'],
                                'active' => strpos($url, $this->id . '/') !== false ? 1 : 0,
                            ];
                        } else {
                            if (strpos($url, $this->id . '/') !== false) {
                                $list[$menu['parent_id']]['active'] = 1;
                            }
                            $list[$menu['parent_id']]['child'][] = [
                                'name' => $menu['menu_name'],
                                'url' => $url,
                                'icon' => $menu['icon'],
                                'active' => strpos($url, $this->id . '/') !== false ? 1 : 0,
                            ];
                        }
                    }
                }
            }
        }
        $this->_menu = $list;
    }

    /**
     * 返回json字符串
     * 程序中应该减少使用die exit等强制中断命令 增加mimetype输出
     * @param $code
     * @param $msg
     * @param $data
     * @return string
     */
    protected function jsonReturn($code, $msg = '', $data = null)
    {
        if($data === null) {
            $data = [['result' => 0]];
        }
        $resAll = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];

        // 返回JSON数据格式到客户端 包含状态信息
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($resAll, JSON_UNESCAPED_UNICODE));
    }

    /**
     * [getParam 统一数据请求获取接口]
     * 由于yii能根据配置中的数据源格式自动解析数据，所以不需要再解析raw post
     * @param  [type] $name [参数名]
     * @return [type]       [参数对应请求值]
     */
    protected function getParam($name)
    {
        // 获取get参数
        $request = \Yii::$app->request;
        $value   = $request->getQueryParam($name);

        // 获取post参数
        if (empty($value)) {
            $value = $request->post($name);
        }
        if(empty($value)) {
            $rawInput = $request->getRawBody();
            try {
                $post = json_decode($rawInput, true);
                if($post) {
                    $_SERVER["CONTENT_TYPE"] = 'application/json; charset=UTF-8';
                    $_SERVER["HTTP_CONTENT_TYPE"] = 'application/json; charset=UTF-8';
                    $request->setBodyParams(null);
                    $_POST = array_merge($_POST, $post);
                    $value = isset($post[$name]) ? $post[$name] : '';
                }
            }catch(Exception $e) {

            }
        }
        return $this->escape($value);
    }

    /**
     * mysql注入安全过滤函数
     * @param  [type]  $str   需要安全过滤的数据
     * @param  integer $depth 过滤深度
     * @return [type]         安全处理后的字符串
     */
    protected function escape($str, $depth = 0)
    {
        if (is_array($str) && $depth < 5) {
            return $this->escape($str, ++$depth);
        } else if (is_array($str) && $depth > 4) {
            return $str;
        }
        $search  = array("\\", "\0", "\n", "\r", "\x1a", "'", '"', ';', '|', ' AND ', ' OR ', '--', '%', '(', '[', ' FROM ', '\\x');
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\Z", "\\'", '\\"', '\\;', '\\|', '&nbsp;AND&nbsp;', '&nbsp;OR&nbsp;', '\\-\\-', '\\%', '\\(', '\\[', '&nbsp;FROM&nbsp;', '\\\\x');
        return str_ireplace($search, $replace, $str);
    }
}
