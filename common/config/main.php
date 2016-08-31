<?php
return [
    'vendorPath'     => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone'       => 'PRC',
    'language'       => 'zh-CN',
    'sourceLanguage' => 'zh-CN',
    'bootstrap'      => ['log'],
    'controllerMap' => [
        'ueditor' => [
            'class' => 'common\vendor\ueditor\UEditorController',
            'thumbnail' => true,//如果将'thumbnail'设置为空，将不生成缩略图。
            'watermark' => [    //默认不生成水印
                'path' => '', //水印图片路径
                'start' => [0, 0] //水印图片位置
            ],
            'zoom' => ['height' => 500, 'width' => 500], //缩放，默认不缩放
            'config' => [
                //server config @see http://fex-team.github.io/ueditor/#server-config
                'imagePathFormat' => '/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
                'scrawlPathFormat' => '/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
                'snapscreenPathFormat' => '/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
                'catcherPathFormat' => '/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
                'videoPathFormat' => '/upload/video/{yyyy}{mm}{dd}/{time}{rand:6}',
                'filePathFormat' => '/upload/file/{yyyy}{mm}{dd}/{rand:4}_{filename}',
                'imageManagerListPath' => '/upload/image/',
                'fileManagerListPath' => '/upload/file/',
            ],
        ],
    ],
    'components'     => [
        'db'           => [
            'class'    => 'yii\db\Connection',
            'dsn'      => 'mysql:host=127.0.0.1;dbname=yshop',
            'username' => 'root',
            'password' => 'pWl0a+HApyLp',
            'charset'  => 'utf8',
            'enableSchemaCache' => FALSE,
            'schemaCacheDuration' => 3600,
            'schemaCache' => 'cache'
        ],
        'request' => [
           'cookieValidationKey' => '345r349%%@#',
        ],
        'redis'        => [
            'class'    => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
//            'password' => '',
            'port'     => 6379,
            'database' => 0,
        ],
        'cache'        => [
            'class' => 'yii\redis\Cache',
        ],
        'session'      => [
            'class' => 'yii\redis\Session',
        ],
        'formatter'    => [
            'class'           => 'yii\i18n\Formatter',
            'dateFormat'      => 'php:Y-m-d',
            'datetimeFormat'  => 'php:Y-m-d H:i:s',
            'timeFormat'      => 'php:H:i:s',
            'defaultTimeZone' => 'PRC',
        ],
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
        ],
        'log'          => [
            'traceLevel' => TRACE_LEVEL,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'except' => [
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:403',
                    ],
                ],
                [
                    'class'       => 'yii\log\FileTarget',
                    'levels'      => ['info', 'error', 'trace', 'warning'],
                    'categories'  => ['yii\\db\\command::*'],
                    'logFile'     => '@app/runtime/logs/db/query.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 1000,
                ],
                [
                    'class'       => 'yii\log\FileTarget',
                    'levels'      => ['info', 'error', 'trace', 'warning'],
                    'categories'  => ['admin'],
                    'logFile'     => '@app/runtime/logs/admin/admin.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 1000,
                    'except' => [
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:403',
                    ],
                ],
                [
                    'class'       => 'yii\log\FileTarget',
                    'levels'      => ['info', 'error', 'trace', 'warning'],
                    'categories'  => ['home'],
                    'logFile'     => '@app/runtime/logs/home/home.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 1000,
                ],
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
    ],
];