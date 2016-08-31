<?php
/**
 * Created by PhpStorm.
 * User: Honvid
 * Date: 16/5/6
 * Time: 上午10:03
 */

namespace common\helpers;

use Yii;
use common\models\MallRoute;

class RouteHelper
{
    protected static $remove_actions = [
        '__construct',
        'init',
        'actionError',
        'actions',
        '__set',
        'get',
        '__get',
        '__isset',
        '__call',
        'jsonReturn'
    ];

    /**
     * 获取指定项目中的所有路由,包括模块名称和方法名称
     * @param $modules
     * @return array
     */
    public static function route_list($modules)
    {
        $data = [];
        foreach ($modules as $key => $module) {
            $controllers = static::get_controllers($module);
            $temp = [
                'module' => $key,
                'title' => $key.'管理',
                'route' => strtolower('/' . $module . '/*'),
                'desc' => '所有权限',
                'level' => 1,
                'parent_id' => 0,
            ];
            $data[] = $temp;
            $id = MallRoute::save_route($temp, $temp['route']);
            $first = '';
            foreach ($controllers as $controller) {
                $controller_name = $controller['name'];
                if($controller_name == 'Base'){
                    continue;
                }
                if($first == '' || $first != $controller_name){
                    $first = $controller_name;
                    $temp = [
                        'module' => $key,
                        'title' => $controller['title'],
                        'route' => strtolower('/' . $module . '/' . $controller_name . '/*'),
                        'desc' => '所有权限',
                        'level' => 2,
                        'parent_id' => $id,
                    ];
                    $second_id = MallRoute::save_route($temp, $temp['route']);
                    $data[] = $temp;
                }
                $actions = static::get_actions($module, $controller_name);
                foreach ($actions as $action) {
                    $desc = static::get_action_desc($module, $controller_name, $action);
                    $temp = [
                        'module' => $key,
                        'title' => $controller['title'],
                        'route' => strtolower('/' . $module . '/'. $controller_name . '/' . $action),
                        'desc' => !empty($desc) ? $desc : '暂无描述',
                        'level' => 3,
                        'parent_id' => $second_id,
                    ];
                    MallRoute::save_route($temp, $temp['route']);
                    $data[] = $temp;
                }
            }
        }
        return $data;
    }

    /**
     * 获取指定项目中的所有路由,包括模块名称和方法名称
     * @param $modules
     * @return array
     */
    public static function route_list_for_role($modules)
    {
        $data = [];
        $i = 0;
        $j = 0;
        foreach ($modules as $key => $module) {
            $controllers = static::get_controllers($module);
            $data[$i] = [
                'module' => $key,
                'title' => $key.'管理',
                'route' => strtolower('/' . $module . '/*'),
                'desc' => '所有权限',
                'level' => 1,
            ];
            $first = '';
            foreach ($controllers as $controller) {
                $controller_name = $controller['name'];
                if($controller_name == 'Base') {
                    continue;
                }
                if($first == '' || $first != $controller_name){
                    $first = $controller_name;
                    $data[$i]['controllers'][$j] = [
                        'module' => $key,
                        'title' => $controller['title'],
                        'route' => strtolower('/' . $module . '/' . $controller_name . '/*'),
                        'desc' => '所有权限',
                        'level' => 2,
                    ];
                }
                $actions = static::get_actions($module, $controller_name);
                foreach ($actions as $action) {
                    $desc = static::get_action_desc($module, $controller_name, $action);
                    $data[$i]['controllers'][$j]['actions'][] = [
                        'module' => $key,
                        'title' => $controller['title'],
                        'route' => strtolower('/' . $module . '/'. $controller_name . '/' . $action),
                        'desc' => !empty($desc) ? $desc : '暂无描述',
                        'level' => 3,
                    ];
                }
                $j++;
            }
            $i++;
        }
        return $data;
    }

    /**
     * 获取指定项目中的所有控制器名称
     * @param $module
     * @return array|null
     */
    protected static function get_controllers($module)
    {
        $path = dirname(Yii::$app->basePath) . DIRECTORY_SEPARATOR . $module . '/controllers/';
        if(!is_dir($path)) {
            return null;
        }
        $path .= '/*.php';
        $ary_files = glob($path);
        $files = [];
        foreach ($ary_files as $file) {
            if (is_dir($file)) {
                continue;
            }else {
                $content = file_get_contents($file);
                preg_match('/Class(.*?)\n/', $content, $match);
                $files[] = [
                    'title' => trim(array_pop($match)),
                    'name' => basename($file, 'Controller.php'),
                ];
            }
        }
        return $files;
    }

    /**
     * 获取某模块下面指定控制器的所有方法
     * @param $module
     * @param $controller
     * @return array|null
     */
    protected static function get_actions($module, $controller)
    {
        if(empty($controller)) {
            return null;
        }
        $controller_name = dirname(Yii::$app->basePath) . DIRECTORY_SEPARATOR . $module . '/controllers/' . $controller . 'Controller.php';
        $content = file_get_contents($controller_name);

        preg_match_all("/.*?public.*?function(.*?)\(.*?\)/i", $content, $matches);

        $functions_list = $matches[1];

        $functions = [];
        foreach ($functions_list as $function){
            $function = trim($function);
            if(!in_array($function, static::$remove_actions)){
                if (strlen($function) > 0)   $functions[] = substr($function, 6);
            }
        }
        return $functions;
    }

    /**
     * 获取函数的注释
     * @param $module
     * @param $controller
     * @param string $action
     * @return null|string
     */
    protected static function get_action_desc($module, $controller, $action = 'index'){
        if(empty($controller) || empty($controller)) {
            return null;
        }
        $controller_name = $module . '\\controllers\\' . $controller . 'Controller';
        $function  = new \ReflectionMethod(new $controller_name($controller, $module), 'action'.$action);
        $flag = preg_match_all('/.*?\n.*?\*(.*?)\n/', $function->getDocComment(), $match);
        if($flag === false) {
            return '暂无描述';
        }
        return trim(array_pop($match[1]));
    }
}