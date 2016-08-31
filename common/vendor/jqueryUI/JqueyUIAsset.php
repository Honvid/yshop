<?php
namespace common\vendor\jqueryUI;

use yii\web\AssetBundle;
use yii;

/**
 * sweet-submit asset.
 *
 * @version 1.0.0
 *
 * @author lichunqiang <light-li@hotmail.com>
 */
class JqueryUIAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $js = [
        'jquery-ui.min.js',
    ];

    /**
     * {@inheritdoc}
     */
    public $css = [
        '',
    ];
    /**
     * {@inheritdoc}
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $sourcePath = '@jqueryUI/assets';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        Yii::setAlias('@jqueryUI', __DIR__);
        parent::init();
    }
}
