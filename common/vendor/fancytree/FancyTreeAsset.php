<?php
namespace common\vendor\fancytree;

use yii\web\AssetBundle;
use yii;

/**
 * sweet-submit asset.
 *
 * @version 1.0.0
 *
 * @author lichunqiang <light-li@hotmail.com>
 */
class FancyTreeAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $js = [
        'dist/jquery-ui.min.js',
        'dist/jquery.fancytree-all.min.js',
    ];

    /**
     * {@inheritdoc}
     */
    public $css = [
        'dist/skin-awesome/ui.fancytree.min.css',
    ];
    /**
     * {@inheritdoc}
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $sourcePath = '@fancytree/assets';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        Yii::setAlias('@fancytree', __DIR__);
        parent::init();
    }
}
