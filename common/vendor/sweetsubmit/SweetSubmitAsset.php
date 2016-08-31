<?php

/*
 * This file is part of the light/yii2-sweet-submit.
 *
 * (c) lichunqiang <light-li@hotmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace common\vendor\sweetsubmit;

use yii\web\AssetBundle;
use yii;

/**
 * sweet-submit asset.
 *
 * @version 1.0.0
 *
 * @author lichunqiang <light-li@hotmail.com>
 */
class SweetSubmitAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $js = [
        'yii.enhance.js'
    ];

    /**
     * {@inheritdoc}
     */
    public $depends = [
        'yii\web\YiiAsset',
    ];

    public $sourcePath = '@sweetsubmit/assets';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        Yii::setAlias('@sweetsubmit', __DIR__);
        parent::init();
    }
}
