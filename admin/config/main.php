<?php
return [
    'id' => 'admin',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'admin\controllers',
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager', //用数据库管理RBAC
            'itemTable' => 'mall_auth_item',
            'assignmentTable' => 'mall_auth_assignment',
            'itemChildTable' => 'mall_auth_item_child',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
];