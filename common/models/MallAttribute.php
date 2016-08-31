<?php

namespace common\models;

use Yii;
use common\models\MallGoodsType;

/**
 * This is the model class for table "mall_attribute".
 *
 * @property integer $attr_id
 * @property integer $cat_id
 * @property string $attr_name
 * @property integer $attr_input_type
 * @property integer $attr_type
 * @property string $attr_values
 * @property integer $attr_index
 * @property integer $sort_order
 * @property integer $is_linked
 * @property integer $attr_group
 */
class MallAttribute extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'attr_input_type', 'attr_type', 'attr_index', 'sort_order', 'is_linked', 'attr_group'], 'integer'],
            [['attr_values'], 'string'],
            [['attr_name'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attr_id' => '属性ID',
            'cat_id' => '类型ID',
            'attr_name' => '属性名称',
            'attr_input_type' => '表单类型 0:手工录入 1:从列表中选择 2:多行文本',
            'attr_type' => '属性是否可选 0:唯一属性 1:单选属性 2:复选属性',
            'attr_values' => '可选值列表',
            'attr_index' => '是否能够检索 0:不需要 1:关键词检索 2:范围检索',
            'sort_order' => 'Sort Order',
            'is_linked' => '相同属性值的商品是否关联 0:否 1:是',
            'attr_group' => '属性分组',
        ];
    }

    /**
     * 列出符合条件的属性
     * @param int $cat_id
     * @param array $filter
     * @return array
     */
    public static function lists($cat_id = 0,$filter = [])
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
                $sorts['attr_id'] = SORT_DESC;
                $query->addOrderBy($sorts);
            }

            if (!empty($filter['keys'])) {
                $query->andWhere("attr_name LIKE '%{$filter['keys']}%'");
            }

            if(!empty($cat_id)){
                $query->andWhere(["cat_id" => intval($cat_id)]);
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
//        $sql = $command->getRawSql();
        $list = $command->queryAll();
        $type_list = MallGoodsType::get_id_name_list();
        foreach ($list as $k => $l){
            if(!empty($type_list[$l['cat_id']])) {
                $list[$k]['cat_name'] = $type_list[$l['cat_id']];
            }else{
                $list[$k]['cat_name'] = '未定义类型';
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
     * 通过属性ID查询
     * @param  [type] $cat_id [description]
     * @return [type]         [description]
     */
    public static function find_by_attr_id($attr_id)
    {
        return self::find()->where(['attr_id' => $attr_id])->asArray()->one();
    }

    /**
     * 通过类型ID查询该类型的属性
     * @param  [type] $cat_id [description]
     * @return [type]         [description]
     */
    public static function find_by_cat_id($cat_id)
    {
        return self::find()->where(['cat_id' => $cat_id])->asArray()->all();
    }

    /**
     * 通过类型ID查询该类型的属性的数量
     * @param  [type] $cat_id [description]
     * @return [type]         [description]
     */
    public static function count_by_cat_id($cat_id)
    {
        return self::find()->where(['cat_id' => $cat_id])->count();
    }

    /**
     * 新建或者编辑属性
     * @param  [type] $data     [description]
     * @param  [type] $attr_id  [description]
     * @return [type]           [description]
     */
    public static function update_attribute($data, $attr_id)
    {
        if(!empty($attr_id)){
            $attribute = self::find()->where([ 'attr_id' => intval($attr_id)])->one();
            if(empty($attribute)){
                $attribute = new self();
            }
        }else{
            $attribute = new self();
        }
        $attribute->load($data, '');
        if(!$attribute->save()){
            return fa;
        }else{
            return $attribute->attributes['attr_id'];
        }
    }

    /**
     * 删除属性
     * @param  [type] $attr_id [description]
     * @return [type]           [description]
     */
    public static function delete_attribute($attr_id)
    {
        MallGoodsAttr::deleteAll(['attr_id' => intval($attr_id)]);
        self::deleteAll(['attr_id' => intval($attr_id)]);
        return true;
    }

    /**
     * 组装商品属性DOM
     * @param $cat_id
     * @param $goods_id
     */
    public static function create_attr_html($cat_id, $goods_id)
    {
        if(empty($cat_id)){
            return '';
        }
        $query = self::find();
        $query->select('a.*, v.attr_value, v.attr_price');
        $query->from(self::tableName() . ' as a');
        $query->leftJoin('mall_goods_attr as v', 'v.attr_id = a.attr_id AND v.goods_id = ' . intval($goods_id));
        $query->where(['a.cat_id' => intval($cat_id)]);
        $query->orWhere(['a.cat_id' => 0]);
        $query->orderBy('a.sort_order, a.attr_type, a.attr_id, v.attr_price, v.goods_attr_id');
        $result = $query->createCommand()->queryAll();
        $html = '';

        $flag = 0;
        $temp = [];

        foreach ($result as $v){
            if($flag != $v['attr_id']){
                $temp[$v['attr_id']] = $v;
            }else{
                $old = $temp[$flag];
                if(count($old) != 12){
                    $temp[$v['attr_id']][] = $v;
                }else {
                    $temp[$v['attr_id']] = [
                        $old,
                        $v,
                    ];
                }
            }
            $flag = $v['attr_id'];
        }

        $space = str_repeat(' ', 56);
        foreach ($temp as $k => $v){
            if(count($v) != 12){
                foreach ($v as $key => $value){
                    if ($key == 0) {
                        $html .= <<<HTML
                        \n
{$space}<div class="col-sm-6 form-group">
{$space}    <label for="attr_{$k}_{$key}" class="col-sm-3 control-label text-right" style="padding:0;">{$value['attr_name']}</label>
{$space}    <div class="col-sm-9">\n
HTML;
                    }
                    $html .= <<<HTML
{$space}        <div class="attr-group">
{$space}            <div class="col-sm-4" style="padding: 0;">
{$space}                <input type="hidden" name="attr_id_list[]" value="{$k}" />

HTML;
                    // 根据输入类型组装输出DOM
                    if($value['attr_input_type'] == 0) {
                        $html .= <<<HTML
{$space}                <input type="text" class="form-control" id="attr_{$k}_{$key}" name="attr_value_list[]" value="{$value['attr_value']}">
HTML;
                    }elseif($value['attr_input_type'] == 2){
                        $html .= <<<HTML
{$space}                <textarea class="form-control" rows="3" name="attr_value_list[]" id="price_{$key}">{$value['attr_value']}</textarea>
HTML;
                    }else{
                        $html .= <<<HTML
{$space}                <select class="form-control select2" name="attr_value_list[]" style="position: absolute;">
{$space}                    <option value="-1">请选择</option>

HTML;
                        $attr_values = explode(",", $value['attr_values']);
                        foreach ($attr_values as $val) {
                            if($value['attr_value'] != $val) {
                                $html .= <<<HTML
{$space}                    <option value="{$val}">{$val}</option>\n
HTML;
                            }else{
                                $html .= <<<HTML
{$space}                    <option value="{$val}" selected>{$val}</option>\n
HTML;
                            }
                        }
                        $html .= <<<HTML
{$space}                </select>\n
HTML;
                    }
                    $html .= <<<HTML
{$space}            </div>
{$space}            <div class="col-sm-6" style="padding: 0;">
{$space}                <div class="row">
{$space}                    <label class="col-sm-4" style="line-height: 34px;padding: 0 0 0 20px;"> 价格:</label>
{$space}                    <input type="number" class="form-controls col-sm-8" name="attr_price_list[]" value="{$value['attr_price']}" />
{$space}                </div>
{$space}            </div>
{$space}            <div class="col-sm-2" style="padding: 0;">\n
HTML;
                    if ($key == 0) {
                        $html .= <<<HTML
{$space}                <span class="input-group-btn">
{$space}                    <a href="javascript:;" class="btn btn-info btn-flat attr-add"><span class="fa fa-plus"></span></a>
{$space}                </span>

HTML;
                    } else {
                        $html .= <<<HTML
{$space}                <span class="input-group-btn">
{$space}                    <a href="javascript:;" class="btn btn-warning btn-flat attr-delete"><span class="fa fa-remove"></span></a>
{$space}                </span>

HTML;
                    }
                    $html .= <<<HTML
{$space}            </div>
{$space}        </div>\n
HTML;
                    if ($key == count($v) - 1) {
                        $html .= <<<HTML
{$space}    </div>
{$space}</div>
HTML;
                    }
                }
            }else {
                $html .= <<<HTML
                \n
{$space}<div class="col-sm-6 form-group">
{$space}    <label for="attr_{$k}" class="col-sm-3 control-label text-right" style="padding:0;">{$v['attr_name']}</label>
{$space}    <div class="col-sm-9">\n
HTML;
                if ($v['attr_input_type'] == 0) {
                    // 单行输入
                    if ($v['attr_type'] == 1 || $v['attr_type'] == 2) {
                        $html .= <<<HTML
{$space}    <div class="attr-group">
{$space}            <div class="col-sm-4" style="padding: 0;">
{$space}                <input type="hidden" name="attr_id_list[]" value="{$v['attr_id']}" />
{$space}                <input type="text" class="form-control" id="attr_{$k}" name="attr_value_list[]" value="{$v['attr_value']}">
{$space}            </div>
{$space}            <div class="col-sm-6" style="padding: 0;">
{$space}                <div class="row">
{$space}                    <label class="col-sm-4" style="line-height: 34px;padding: 0 0 0 20px;"> 价格:</label>
{$space}                    <input type="number" class="form-controls col-sm-8" name="attr_price_list[]" value="{$v['attr_price']}" />
{$space}                </div>
{$space}           </div>
{$space}           <div class="col-sm-2" style="padding: 0;">
{$space}                <span class="input-group-btn">
{$space}                    <a href="javascript:;" class="btn btn-info btn-flat attr-add"><span class="fa fa-plus"></span></a>
{$space}                </span>
{$space}            </div>
{$space}        </div>\n
HTML;
                    } else {
                        $html .= <<<HTML
{$space}        <input type="hidden" name="attr_id_list[]" value="{$v['attr_id']}" />
{$space}        <input type="text" class="form-control" id="attr_{$k}" name="attr_value_list[]" value="{$v['attr_value']}">
{$space}        <input type="hidden" name="attr_price_list[]" value="0" />\n
HTML;
                    }
                } else if ($v['attr_input_type'] == 2) {
                    // 多行输入
                    if ($v['attr_type'] == 1 || $v['attr_type'] == 2) {
                        $html .= <<<HTML
{$space}        <div class="attr-group">
{$space}            <div class="col-sm-4" style="padding: 0;">
{$space}                <input type="hidden" name="attr_id_list[]" value="{$v['attr_id']}" />
{$space}                <textarea class="form-control" rows="3" name="attr_value_list[]" id="{$k}">{$v['attr_value']}</textarea>
{$space}            </div>
{$space}            <div class="col-sm-6" style="padding: 0;">
{$space}                <div class="row">
{$space}                    <label class="col-sm-4" style="line-height: 34px;padding: 0 0 0 20px;"> 价格:</label>
{$space}                    <input type="number" class="form-controls col-sm-8" name="attr_price_list[]" value="{$v['attr_price']}" />
{$space}                </div>
{$space}            </div>
{$space}            <div class="col-sm-2" style="padding: 0;">
{$space}                <span class="input-group-btn">
{$space}                    <a href="javascript:;" class="btn btn-info btn-flat attr-add"><span class="fa fa-plus"></span></a>
{$space}                </span>
{$space}            </div>
{$space}        </div>\n
HTML;
                    } else {
                        $html .= <<<HTML
{$space}        <input type="hidden" name="attr_id_list[]" value="{$v['attr_id']}" />
{$space}        <textarea class="form-control" rows="3" name="attr_value_list[]" id="attr_{$k}">{$v['attr_value']}</textarea>
{$space}        <input type="hidden" name="attr_price_list[]" value="0" />\n
HTML;
                    }

                } else {
                    // 下拉列表
                    if ($v['attr_type'] == 1 || $v['attr_type'] == 2) {
                        $html .= <<<HTML
{$space}        <div class="attr-group">
{$space}            <div class="col-sm-4" style="padding: 0;">
{$space}                <input type="hidden" name="attr_id_list[]" value="{$v['attr_id']}" />
{$space}                <select class="form-control select2" name="attr_value_list[]" style="position: absolute;">
{$space}                    <option value="-1">请选择</option>\n
HTML;
                        $attr_values = explode(",", $v['attr_values']);
                        foreach ($attr_values as $val) {
                            if($v['attr_value'] != $val) {
                                $html .= <<<HTML
{$space}                    <option value="{$val}">{$val}</option>\n
HTML;
                            }else{
                                $html .= <<<HTML
{$space}                    <option value="{$val}" selected>{$val}</option>\n
HTML;
                            }
                        }
                        $html .= <<<HTML
{$space}                </select>
{$space}            </div>
{$space}            <div class="col-sm-6" style="padding: 0;">
{$space}                <div class="row">
{$space}                    <label class="col-sm-4" style="line-height: 34px;padding: 0 0 0 20px;"> 价格:</label>
{$space}                    <input type="number" class="form-controls col-sm-8" name="attr_price_list[]" value="{$v['attr_price']}" />
{$space}                </div>
{$space}            </div>
{$space}            <div class="col-sm-2" style="padding: 0;">
{$space}                <span class="input-group-btn">
{$space}                    <a href="javascript:;" class="btn btn-info btn-flat attr-add"><span class="fa fa-plus"></span></a>
{$space}                </span>
{$space}            </div>
{$space}        </div>\n
HTML;
                    } else {
                        $html .= <<<HTML
{$space}        <input type="hidden" name="attr_id_list[]" value="{$v['attr_id']}" />
{$space}        <select class="form-control select2" name="attr_value_list[]" style="position: absolute;">
{$space}            <option value="-1">请选择</option>
HTML;
                        $attr_values = explode(",", $v['attr_values']);
                        foreach ($attr_values as $val) {
                            if($v['attr_value'] != $val) {
                                $html .= <<<HTML
{$space}            <option value="{$val}">{$val}</option>\n
HTML;
                            }else{
                                $html .= <<<HTML
{$space}            <option value="{$val}" selected>{$val}</option>\n
HTML;
                            }
                        }
                        $html .= <<<HTML
{$space}        </select>
{$space}        <input type="hidden" name="attr_price_list[]" value="0" />
HTML;
                    }
                }
                $html .= <<<HTML
{$space}    </div>
{$space}</div>\n
HTML;
            }
        }

        return $html;
    }
}
