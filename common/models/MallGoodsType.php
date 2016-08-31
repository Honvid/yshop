<?php

namespace common\models;

use Yii;
use common\models\MallAttribute;
use common\models\MallGoodsAttr;

/**
 * This is the model class for table "mall_goods_type".
 *
 * @property integer $cat_id
 * @property string $cat_name
 * @property integer $enabled
 * @property string $attr_group
 */
class MallGoodsType extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_goods_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enabled'], 'integer'],
            [['attr_group'], 'required'],
            [['cat_name'], 'string', 'max' => 60],
            [['attr_group'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat_id' => '自增ID号',
            'cat_name' => '商品类型名',
            'enabled' => '类型状态，1，为可用；0为不可用；不可用的类型，在添加商品的时候选择商品属性将不可选',
            'attr_group' => '商品属性分组，将一个商品类型的属性分成组，在显示的时候也是按组显示。该字段的值显示在属性的前一行，像标题的作用',
        ];
    }

    /**
     * 列出符合条件的产品类型
     * @param  array  $filter [description]
     * @return [type]         [description]
     */
    public static function lists($filter = [])
    {
        $query = self::find();
        $pageSize = 20;
        $page = 1;
        if (!empty($filter)) {
            // 每页显示条数
            if (!empty($filter['pageSize'])) {
                $pageSize = $filter['pageSize'];
                unset($filter['pageSize']);
            }
            // 页码
            if (!empty($filter['page'])) {
                $page = $filter['page'];
                unset($filter['page']);
            }
            // 排序
            if (isset($filter['sort'])) {
                $sorts = array();
                foreach ($filter['sort'] as $key => $sort) {
                    $sorts[$key] = strtoupper($sort) == 'DESC' ? SORT_DESC : SORT_ASC;
                }
                $query->addOrderBy($sorts);
            } else {
                $sorts = array();
                $sorts['cat_id'] = SORT_DESC;
                $query->addOrderBy($sorts);
            }

            if (!empty($filter['keys'])) {
                $query->andWhere("cat_name LIKE '%{$filter['keys']}%'");
            }

            if (isset($filter['filter'])) {
                foreach ($filter['filter']['filters'] as $key => $_condition) {
                    if(isset($_condition['logic'])) {
                        continue;
                    }
                    $_condition['value'] = is_numeric($_condition['value']) || strtotime($_condition['value']) === false ? $_condition['value'] : date('Y-m-d H:i:s', strtotime($_condition['value']));
                    switch ($_condition['operator']) {
                        case 'eq':
                            $query->andWhere([$_condition['field'] => $_condition['value']]);
                            break;
                        case 'neq':
                            $query->andWhere("`{$_condition['field']}`!='{$_condition['value']}'");
                            break;
                        case 'gt':
                            $query->andWhere("`{$_condition['field']}`>'{$_condition['value']}'");
                            break;
                        case 'lt':
                            $query->andWhere("`{$_condition['field']}`<'{$_condition['value']}'");
                            break;
                        case 'gte':
                            $query->andWhere("`{$_condition['field']}`>='{$_condition['value']}'");
                            break;
                        case 'lte':
                            $query->andWhere("`{$_condition['field']}`<='{$_condition['value']}'");
                            break;
                        case 'contains':
                            $query->andWhere("`{$_condition['field']}` LIKE '%{$_condition['value']}%'");
                            break;
                        default:
                            # code...
                            break;
                    }
                }
            }
        }

        $totalSize = $query->count();
        $totalPage = ceil($totalSize / $pageSize);
        if ($totalPage < $page) {
            $page = 1;
        }
        $query->offset(($page - 1) * $pageSize);
        $query->limit($pageSize);
        $command = $query->createCommand();
        $sql = $command->getRawSql();
        $list = $command->queryAll();
        if(!empty($list)){
            foreach ($list as $key => &$value) {
                $value['number'] = MallAttribute::count_by_cat_id($value['cat_id']);
            }
        }
        return [
            'current' => $page,
            'rowCount' => $pageSize,
            'total' => $totalSize,
            'rows' => $list,
        ];
    }

    /**
     * 商品类型列表
     * @return [type] [description]
     */
    public static function goods_type_list()
    {
        $query = self::find();
        $query->where(['enabled' => 1]);
        $query->asArray();
        return $query->all();
    }

    /**
     * 获取某ID的详情
     * @return [type] [description]
     */
    public static function find_by_id($cat_id)
    {
        $query = self::find();
        $query->where(['cat_id' => intval($cat_id)]);
        $query->asArray();
        return $query->one();
    }  

    /**
     * 新建或者编辑类型
     * @param  [type] $data     [description]
     * @param  [type] $cat_id [description]
     * @return [type]           [description]
     */
    public static function update_type($data, $cat_id)
    {
        if(!empty($cat_id)){
            $type = self::find()->where([ 'cat_id' => intval($cat_id)])->one();
            if(empty($type)){
                $type = new self();
            }
        }else{
            $type = new self();
        }
        $type->load($data, '');
        if(!$type->save()){
            return false;
        }else{
            return $type->attributes['cat_id'];
        }
    }  

    /**
     * 删除类型
     * @param  [type] $cat_id [description]
     * @return [type]           [description]
     */
    public static function delete_type($cat_id)
    {
        $result = self::deleteAll(['cat_id' => intval($cat_id)]);
        if($result){
            $attrs = MallAttribute::find_by_cat_id(intval($cat_id));
            if(!empty($attrs)){
                // 删除该类型的所有属性
                MallAttribute::deleteAll(['cat_id' => intval($cat_id)]);
                // 删除拥有该属性的商品关联关系
                foreach ($attrs as $key => $value) {  
                    MallGoodsAttr::deleteAll(['attr_id' => intval($value['attr_id'])]);
                    MallCategory::update_by_attr(intval($value['attr_id']));
                }
            }
            return true;
        }
        return false;
    }

    /**
     * 取得以cat_id为键,cat_name为值的数组
     */
    public static function get_id_name_list()
    {
        $temp = [];
        $query = self::find();
        $query->where(['enabled' => 1]);
        $query->asArray();
        $result = $query->all();
        foreach ($result as $type)
        {
            $temp[$type['cat_id']] = $type['cat_name'];
        }
        return $temp;
    }

    /**
     * 取得某类型的属性分组
     * @param $cat_id
     */
    public static function get_attr_group_by_id($cat_id)
    {
        $group = self::find()->select('attr_group')->where(['cat_id' => intval($cat_id), 'enabled' => 1])->asArray()->one();
        if(!empty($group['attr_group'])){
            return $group = explode(',', $group['attr_group']);
        }
        return [];
    }
}
