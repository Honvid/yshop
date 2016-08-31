<?php
namespace common\vendor\ztree;

use yii\web\AssetBundle;
use yii;

/**
 * sweet-submit asset.
 *
 * @version 1.0.0
 *
 * @author lichunqiang <light-li@hotmail.com>
 */
class ZTreeAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $js = [
        'js/jquery.ztree.all.min.js',
    ];

    /**
     * {@inheritdoc}
     */
    public $css = [
//        'css/demo.css',
//        'css/zTreeStyle/zTreeStyle.css',
        'css/awesomeStyle/awesome.css',
    ];
    /**
     * {@inheritdoc}
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $sourcePath = '@ztree/assets';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        Yii::setAlias('@ztree', __DIR__);
        parent::init();
    }
}
