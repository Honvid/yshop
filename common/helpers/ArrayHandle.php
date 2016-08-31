<?php
namespace common\helpers;

use Yii;
/**
* 数组处理
*/
class ArrayHandle
{
    /**
     * 过滤和排序所有分类，返回一个带有缩进级别的数组
     * @param $category_id
     * @param $arr
     * @return array
     */
    public static function cat_options($category_id, $arr)
    {
        $level = $last_cat_id = 0;
        $options = $cat_id_array = $level_array = array();
        while (!empty($arr)){
            foreach ($arr as $key => $value){
                $cat_id = $value['cat_id'];
                if ($level == 0 && $last_cat_id == 0){
                    if ($value['parent_id'] > 0){
                        break;
                    }

                    $options[$cat_id]          = $value;
                    $options[$cat_id]['level'] = $level;
                    $options[$cat_id]['id']    = $cat_id;
                    $options[$cat_id]['name']  = $value['cat_name'];
                    unset($arr[$key]);

                    if ($value['has_children'] == 0){
                        continue;
                    }
                    $last_cat_id  = $cat_id;
                    $cat_id_array = array($cat_id);
                    $level_array[$last_cat_id] = ++$level;
                    continue;
                }

                if ($value['parent_id'] == $last_cat_id){
                    $options[$cat_id]          = $value;
                    $options[$cat_id]['level'] = $level;
                    $options[$cat_id]['id']    = $cat_id;
                    $options[$cat_id]['name']  = $value['cat_name'];
                    unset($arr[$key]);

                    if ($value['has_children'] > 0){
                        if (end($cat_id_array) != $last_cat_id)
                        {
                            $cat_id_array[] = $last_cat_id;
                        }
                        $last_cat_id    = $cat_id;
                        $cat_id_array[] = $cat_id;
                        $level_array[$last_cat_id] = ++$level;
                    }
                }elseif ($value['parent_id'] > $last_cat_id) {
                    break;
                }
            }

            $count = count($cat_id_array);
            if ($count > 1){
                $last_cat_id = array_pop($cat_id_array);
            } elseif ($count == 1) {
                if ($last_cat_id != end($cat_id_array)){
                    $last_cat_id = end($cat_id_array);
                } else {
                    $level = 0;
                    $last_cat_id = 0;
                    $cat_id_array = array();
                    continue;
                }
            }

            if ($last_cat_id && isset($level_array[$last_cat_id])){
                $level = $level_array[$last_cat_id];
            } else {
                $level = 0;
            }
        }
        // cat_id 为0 或者未传值时
        if (empty($category_id)){
            return $options;
        } else {
            // 如果分类信息里面没有该cat_id的信息则返回空
            if (empty($options[$category_id])){
                return [];
            }
            $cat_id_level = $options[$category_id]['level'];
            foreach ($options as $key => $value) {
                if ($key != $category_id){
                    unset($options[$key]);
                } else {
                    break;
                }
            }
            $cat_id_array = array();
            foreach ($options as $key => $value) {
                if (($cat_id_level == $value['level'] && $value['cat_id'] != $category_id) ||
                    ($cat_id_level > $value['level'])){
                    break;
                } else {
                    $cat_id_array[$key] = $value;
                }
            }
            return $cat_id_array;
        }
    }

    /**
     * 获取树形菜单结构
     * @param $array
     * @param int $pid
     * @return array
     */
    public static function create_menu_tree($array, $pid = 0)
    {
        if (empty($array)) {
            return [];
        }
        static $tree = [];
        static $level = 0;
        $level++;
        foreach ($array as $k => $v) {
            if (!isset($v['menu_id']) || !isset($v['parent_id'])) {
                continue;
            }
            if ($v['parent_id'] == $pid) {
                $v['level'] = $level;
                $tree[$v['menu_id']] = $v;
                if($v['has_children'] > 0) {
                    self::create_menu_tree($array, $v['menu_id']);
                }
            }
        }
        $level--;
        return $tree;
    }

    /**
     * 获取树形地区结构
     * @param $array
     * @return array
     */
    public static function create_area_tree($array)
    {

    }
}