<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace admin\assets;

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
        'css/bootstrap.min.css',
        'css/font-awesome.min.css',
        'css/jquery.bootgrid.min.css',
        'js/select2/select2.min.css',
        'css/AdminLTE.min.css',
        'css/skins/skin-blue.min.css',
        'css/base.css'
    ];
    public $js = [
        'js/bootstrap.min.js',
        'js/jquery.bootgrid.js',
        'js/select2/select2.full.min.js',
        'js/moment.min.js',
        'js/app.min.js',
        'js/base.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'common\vendor\sweetsubmit\SweetSubmitAsset',
    ];

    /**
     * 定义要加载的JS文件
     * @param [type] $view   [description]
     * @param [type] $jsfile [description]
     */
    public static function addJs($view, $jsfile) {  
        $view->registerJsFile($jsfile, [AppAsset::className(), 'depends' => 'admin\assets\AppAsset']);  
    }  
      
    /**
     * 定义要加载的CSS文件
     * @param [type] $view    [description]
     * @param [type] $cssfile [description]
     */
    public static function addCss($view, $cssfile) {  
        $view->registerCssFile($cssfile, [AppAsset::className(), 'depends' => 'admin\assets\AppAsset']);  
    }  
}
