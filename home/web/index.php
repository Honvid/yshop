<?php
define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(__DIR__) . DS);
define('BASE_PATH', dirname(dirname(__DIR__)) . DS);
define('CORE_PATH', BASE_PATH . 'vendor' . DS);
define('COMMON_PATH', BASE_PATH . 'common' . DS);
$env = getenv('RUNTIME_ENVIROMENT');
$config = [];
switch ($env) {
    case 'live':
    case 'prod':
        defined('YII_DEBUG') or define('YII_DEBUG', false);
        defined('YII_ENV') or define('YII_ENV', 'prod');
        defined('TRACE_LEVEL') or define('TRACE_LEVEL', 0);
        break;
    case 'test':
        defined('YII_DEBUG') or define('YII_DEBUG', true);
        defined('YII_ENV') or define('YII_ENV', 'test');
        defined('TRACE_LEVEL') or define('TRACE_LEVEL', 0);
        break;
    case 'dev':
        defined('YII_DEBUG') or define('YII_DEBUG', true);
        defined('YII_ENV') or define('YII_ENV', 'dev');
        defined('TRACE_LEVEL') or define('TRACE_LEVEL', 3);
        $config['bootstrap'][]      = 'debug';
        $config['modules']['debug'] = 'yii\debug\Module';
        $config['bootstrap'][]    = 'gii';
        $config['modules']['gii'] = 'yii\gii\Module';
        break;
    default:
        // 默认开发环境
        $env = 'dev';
        defined('YII_DEBUG') or define('YII_DEBUG', true);
        defined('YII_ENV') or define('YII_ENV', 'dev');
        defined('TRACE_LEVEL') or define('TRACE_LEVEL', 3);
        $config['bootstrap'][]      = 'debug';
        $config['modules']['debug'] = 'yii\debug\Module';
        // dev 模式下开启gii模块
        $config['bootstrap'][]    = 'gii';
        $config['modules']['gii'] = 'yii\gii\Module';
        break;
}
// 加载Yii核心
require CORE_PATH . 'autoload.php';
require CORE_PATH . 'yiisoft' . DS . 'yii2' . DS . 'Yii.php';
require COMMON_PATH . 'config' . DS . 'bootstrap.php';

// 加载公共配置 
$config = yii\helpers\ArrayHelper::merge(
    $config,
    require (COMMON_PATH . 'config' . DS . 'main.php'), // 公共配置
    require (APP_PATH . 'config' . DS . 'main.php'), // 项目配置
    require (COMMON_PATH . 'config' . DS . "{$env}.php") // 环境配置
);

// 加载全局配置 Yii::$app->params[$key] 
$config['params'] = yii\helpers\ArrayHelper::merge(
    require (COMMON_PATH .'config' . DS . 'params.php'),
    require (COMMON_PATH . 'config' . DS . "params-{$env}.php")
);

$application = new yii\web\Application($config);
$application->run();