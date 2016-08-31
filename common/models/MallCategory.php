<?php
namespace common\models;

use Yii;
use common\helpers\ArrayHandle;

/**
 * This is the model class for table "mall_category".
 *
 * @property integer $cat_id
 * @property string $cat_name
 * @property string $keywords
 * @property string $cat_desc
 * @property integer $parent_id
 * @property integer $sort_order
 * @property string $template_file
 * @property string $measure_unit
 * @property integer $show_in_nav
 * @property string $style
 * @property integer $is_show
 * @property integer $grade
 * @property string $filter_attr
 */
class MallCategory extends BaseModel
{
    const DELETE_ERROR = '不是末级分类或者此分类下还存在有商品,您不能删除!';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort_order', 'show_in_nav', 'is_show', 'grade'], 'integer'],
            [['cat_name'], 'string', 'max' => 90],
            [['keywords', 'cat_desc', 'filter_attr'], 'string', 'max' => 255],
            [['template_file'], 'string', 'max' => 50],
            [['measure_unit'], 'string', 'max' => 15],
            [['style'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat_id' => '自增ID号',
            'cat_name' => '分类名称',
            'keywords' => '分类的关键字，可能是为了搜索',
            'cat_desc' => '分类描述',
            'parent_id' => '该分类的父id，取值于该表的cat_id字段',
            'sort_order' => '该分类在页面显示的顺序，数字越大顺序越靠后；同数字，id在前的先显示',
            'template_file' => '不确定字段，按名字和表设计猜，应该是该分类的单独模板文件的名字',
            'measure_unit' => '该分类的计量单位',
            'show_in_nav' => '是否显示在导航栏，0，不；1，显示在导航栏',
            'style' => '该分类的单独的样式表的包括文件名部分的文件路径',
            'is_show' => '是否在前台页面显示，1，显示；0，不显示',
            'grade' => '该分类的最高和最低价之间的价格分级，当大于1时，会根据最大最小价格区间分成区间，会在页面显示价格范围，如0-300,300-600,600-900这种',
            'filter_attr' => '如果该字段有值，则该分类将还会按照该值对应在表goods_attr的goods_attr_id所对应的属性筛选，如，封面颜色下有红，黑分类筛选 ',
        ];
    }

    /**
     * 查询某分类详情
     * @param  [type] $cat_id [description]
     * @return [type]         [description]
     */
    public static function find_by_id($cat_id)
    {
        return self::find()->where(['cat_id' => intval($cat_id)])->asArray()->one();
    }

    /**
     * 获得指定分类下的子分类的数组
     * @param   int     $cat_id     分类的ID
     * @param   int     $level      限定返回的级数。为0时返回所有级数
     * @param   int     $is_show_all 如果为true显示所有分类，如果为false隐藏不可见分类。
     * @return [type] [description]
     */
    public static function category_list($cat_id = 0, $level = 0, $is_show_all = true)
    {
        // 列出所有的分类
        $query = self::find();
        $query->select('c.cat_id, c.cat_name, c.measure_unit, c.parent_id, c.is_show, c.show_in_nav, c.grade, c.sort_order, COUNT(s.cat_id) AS has_children')->from('mall_category AS c')->leftJoin('mall_category AS s', 's.parent_id=c.cat_id');
        $res = $query->groupBy('c.cat_id')->orderBy('c.parent_id ASC, c.sort_order ASC')->asArray()->all();
        if(empty($res)){
            return [];
        }
        // 取出分类下面有效产品的数量
        $goods_num = MallGoods::count_by_category();
        foreach ($res as $key => &$value) {
            $value['goods_num'] = !empty($goods_num[$value['cat_id']]) ? $goods_num[$value['cat_id']] : 0;
        }

        $options = ArrayHandle::cat_options($cat_id, $res);

        $children_level = 99999; //大于这个分类的将被删除
        if ($is_show_all == false){
            // 隐藏不可见分类
            foreach ($options as $key => $val) {
                if ($val['level'] > $children_level) {
                    unset($options[$key]);
                } else {
                    if ($val['is_show'] == 0) {
                        unset($options[$key]);
                        if ($children_level > $val['level']) {
                            $children_level = $val['level']; // 标记一下，这样子分类也能删除
                        }
                    } else {
                        $children_level = 99999; //恢复初始值
                    }
                }
            }
        }

        // 截取到指定的缩减级别
        if ($level > 0) {
            if ($cat_id == 0) {
                $end_level = $level;
            } else {
                $first_item = reset($options); // 获取第一个元素
                $end_level  = $first_item['level'] + $level;
            }

            // 保留level小于end_level的部分
            foreach ($options as $key => $val) {
                if ($val['level'] >= $end_level) {
                    unset($options[$key]);
                }
            }
        }
        return $options;
    }

    /**
     * 新建或者编辑分类
     * @param  [type] $data     [description]
     * @param  [type] $cat_id [description]
     * @return [type]           [description]
     */
    public static function update_category($data, $cat_id)
    {
        if(!empty($cat_id)){
            $category = self::find()->where([ 'cat_id' => intval($cat_id)])->one();
            if(empty($category)){
                $category = new self();
            }
        }else{
            $category = new self();
        }
        $category->load($data, '');
        if(!$category->save()){
            return false;
        }else{
            return $category->attributes['cat_id'];
        }
    }

    /**
     * 通过一个分类ID获取所有子ID集合
     * @param  integer $cat_id [description]
     * @return [type]          [description]
     */
    public static function get_all_ids($cat_id = 0)
    {
        $query = self::find();
        $cat = $query->where(['cat_id' => $cat_id])->one();
        $ids = [$cat_id];
        if(!empty($cat)){
            $new_query = self::find();
            $children = $new_query->select('cat_id')->where(['parent_id' => $cat->cat_id])->asArray()->all();
            foreach ($children as $value) {
                $ids[] = $value['cat_id'];
            }
            return $ids;
        }
        return false;
    }

    /**
     * 删除分类
     * @param  [type] $cat_id [description]
     * @return [type]           [description]
     */
    public static function delete_category($cat_id)
    {
        $goods = MallGoods::find_by_cat_id($cat_id);
        $category = self::find()->where(['parent_id' => intval($cat_id)])->count();
        if(count($goods) == 0 && $category == 0){
            $result = self::deleteAll(['cat_id' => intval($cat_id)]);
            if($result){
                MallNav::delete_by_cat_id(intval($cat_id));
                return true;
            }
        }else{
            return self::DELETE_ERROR;
        }
        return false;
    }

    /**
     * 分类名查重
     * @param  [type] $cat_name [description]
     * @return [type]           [description]
     */
    public static function find_by_name($cat_name)
    {
        return self::find()->where(['cat_name' => $cat_name])->one();
    }

    /**
     * 更新某一属性值
     * @param  [type] $attr_id [description]
     * @return [type]          [description]
     */
    public static function update_by_attr($attr_id)
    {
        $result = self::find()->select('cat_id, filter_attr')
            ->where('filter_attr like :attr_id', [':attr_id' => '%'.intval($attr_id).'%'])
            ->asArray()->all();
        if(!empty($result)){
            foreach ($result as $key => $value) {
                $attrs = explode(',', $value['filter_attr']);
                $k = array_search($attr_id, $attrs);
                if($k !== false){
                    array_splice($attrs, $k, 1);
                }
                $query = self::find()->where(['cat_id' => $value['cat_id']])->one();
                $query->filter_attr = implode(',', $attrs);
                $query->save();
            }
        }
    }
}
