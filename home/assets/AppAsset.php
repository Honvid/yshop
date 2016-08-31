<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace home\assets;

use yii\web\AssetBundle;

/**
 * @author Honvid
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        ''
    ];
    public $js = [
        '',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
