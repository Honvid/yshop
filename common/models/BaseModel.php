<?php
namespace common\models;

use Yii;

class BaseModel extends \yii\db\ActiveRecord
{
    // 是否导航栏显示
    public static $is_show = [
        0 => 'fa fa-times text-yellow',
        1 => 'fa fa-check text-green'
    ];
}