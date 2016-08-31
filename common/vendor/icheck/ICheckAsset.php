<?php
namespace common\vendor\icheck;

use yii\web\AssetBundle;
use yii;

/**
 * sweet-submit asset.
 *
 * @version 1.0.0
 *
 * @author lichunqiang <light-li@hotmail.com>
 */
class ICheckAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $js = [
        'icheck.min.js',
    ];

    /**
     * {@inheritdoc}
     */
    public $css = [
        'skins/square/blue.css',
    ];
    /**
     * {@inheritdoc}
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $sourcePath = '@icheck/assets';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        Yii::setAlias('@icheck', __DIR__);
        parent::init();
    }
}
