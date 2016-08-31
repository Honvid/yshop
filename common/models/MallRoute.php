<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mall_route".
 *
 * @property integer $id
 * @property string $title
 * @property string $desc
 * @property string $module
 * @property string $route
 * @property integer $parent_id
 * @property integer $level
 */
class MallRoute extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_route';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'desc', 'module', 'route'], 'required'],
            [['parent_id', 'level'], 'integer'],
            [['title', 'module'], 'string', 'max' => 100],
            [['desc'], 'string', 'max' => 200],
            [['route'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '模块名称',
            'desc' => '权限描述',
            'module' => '项目',
            'route' => '路由',
            'parent_id' => '父级ID',
            'level' => '路由层级',
        ];
    }

    /**
     * 添加或者更新权限
     * @param $data
     * @param $route
     * @return bool
     */
    public static function save_route($data, $route)
    {
        if (!empty($route)) {
            $routes = self::find()->where(['route' => $route])->one();
            if (empty($routes)) {
                $routes = new self();
            }
        } else {
            $routes = new self();
        }
        $routes->load($data, '');
        if (!$routes->save()) {
            return false;
        } else {
            return $routes->attributes['id'];
        }
    }
}
