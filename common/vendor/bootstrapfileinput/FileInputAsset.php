<?php
namespace common\vendor\bootstrapfileinput;

use yii\web\AssetBundle;
use yii;

/**
 * sweet-submit asset.
 *
 * @version 1.0.0
 *
 * @author lichunqiang <light-li@hotmail.com>
 */
class FileInputAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $js = [
        'js/fileinput.min.js',
        'js/fileinput_locale_zh.js',
    ];

    /**
     * {@inheritdoc}
     */
    public $css = [
        'css/fileinput.min.css',
    ];
    /**
     * {@inheritdoc}
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $sourcePath = '@fileinput/assets';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        Yii::setAlias('@fileinput', __DIR__);
        parent::init();
    }
}
