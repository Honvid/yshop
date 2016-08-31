<?php

namespace common\models;

use common\helpers\ArrayHandle;
use common\helpers\RedisKey;
use Yii;

/**
 * This is the model class for table "mall_menu".
 *
 * @property integer $menu_id
 * @property string $menu_name
 * @property string $icon
 * @property integer $is_show
 * @property string $menu_rule
 * @property string $desc
 * @property integer $parent_id
 * @property integer $sort
 * @property integer $create_at
 * @property integer $update_at
 */
class MallMenu extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_show', 'parent_id', 'sort', 'create_at', 'update_at'], 'integer'],
            [['menu_name', 'icon', 'menu_rule', 'desc'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_id' => 'Menu ID',
            'menu_name' => 'Menu Name',
            'icon' => 'Icon',
            'is_show' => 'Is Show',
            'menu_rule' => 'Menu Rule',
            'desc' => 'Desc',
            'parent_id' => 'Parent ID',
            'sort' => 'Sort',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * 获取权限列表
     * @param int $menu_id
     * @return array
     */
    public static function get_menu_list($menu_id = 0)
    {
        $query = self::find();
        $query->select('r.*, COUNT(s.menu_id) AS has_children');
        $query->from('mall_menu AS r')->leftJoin('mall_menu AS s', 's.parent_id=r.menu_id');
        $menus = $query->groupBy('r.menu_id')->orderBy('r.parent_id ASC, r.sort ASC')->asArray()->all();
        $menu_list = ArrayHandle::create_menu_tree($menus, $menu_id);
        Yii::$app->redis->set(RedisKey::ADMIN_MENU_LIST, json_encode($menu_list));
        return $menu_list;
    }

    /**
     * 获取权限详情
     * @param $menu_id
     * @return array|null
     */
    public static function get_by_id($menu_id)
    {
        $query = self::find();
        return $query->where(['menu_id' => intval($menu_id)])->asArray()->one();
    }

    /**
     * 添加或者更新权限
     * @param $data
     * @param $menu_id
     * @return bool
     */
    public static function save_menu($data, $menu_id)
    {
        if (!empty($menu_id)) {
            $menu = self::find()->where(['menu_id' => intval($menu_id)])->one();
            if (empty($menu)) {
                $menu = new self();
                $data['create_at'] = time();
            }
        } else {
            $menu = new self();
            $data['create_at'] = time();
        }
        $data['update_at'] = time();
        $menu->load($data, '');
        if (!$menu->save()) {
            return false;
        } else {
            self::get_menu_list();
            return $menu->attributes['menu_id'];
        }
    }

    /**
     * 删除菜单
     * @param $menu_id
     * @return bool
     */
    public static function delete_menu($menu_id)
    {
        if(!empty($menu_id)){
            $query = self::find();
            $find_child = $query->where(['parent_id' => intval($menu_id)])->count();
            if($find_child == 0){
                $result = self::deleteAll(['menu_id' => intval($menu_id)]);
                if($result){
                    self::get_menu_list();
                    return true;
                }
            }
        }
        return false;
    }
}
