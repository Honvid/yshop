<?php

namespace common\models;

use Yii;
use common\models\MallGoods;

/**
 * This is the model class for table "mall_brand".
 *
 * @property integer $brand_id
 * @property string $brand_name
 * @property string $brand_logo
 * @property string $brand_desc
 * @property string $site_url
 * @property integer $sort_order
 * @property integer $is_show
 */
class MallBrand extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_desc'], 'required'],
            [['brand_desc'], 'string'],
            [['sort_order', 'is_show'], 'integer'],
            [['brand_name'], 'string', 'max' => 60],
            [['brand_logo'], 'string', 'max' => 80],
            [['site_url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'brand_id' => '品牌ID',
            'brand_name' => '名称',
            'brand_logo' => 'LOGO',
            'brand_desc' => '品牌描述',
            'site_url' => '官网',
            'sort_order' => '排序',
            'is_show' => '该品牌是否显示，0，否；1，显示',
        ];
    }

    /**
     * 列出符合条件的品牌
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
                $sorts['brand_id'] = SORT_DESC;
                $query->addOrderBy($sorts);
            }

            if (!empty($filter['keys'])) {
                $query->andWhere("brand_name LIKE '%{$filter['keys']}%'");
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
        return [
            'current' => $page,
            'rowCount' => $pageSize,
            'total' => $totalSize,
            'rows' => $list,
        ];
    }

    /**
     * 获取品牌信息
     * @param  [type] $brand_id [description]
     * @return [type]           [description]
     */
    public static function find_by_id($brand_id)
    {
        return self::find()->where(['brand_id' => intval($brand_id)])->asArray()->one();
    }

    /**
     * 取得品牌列表
     * @return [type] [description]
     */
    public static function get_brand_list()
    {
        $query = self::find();
        return $query->orderBy('sort_order')->asArray()->all();
    }

    /**
     * 新建或者编辑品牌
     * @param  [type] $data     [description]
     * @param  [type] $brand_id [description]
     * @return [type]           [description]
     */
    public static function update_brand($data, $brand_id)
    {
        if(!empty($brand_id)){
            $brand = self::find()->where([ 'brand_id' => intval($brand_id)])->one();
            if(empty($brand)){
                $brand = new self();
            }
        }else{
            $brand = new self();
        }
        $brand->load($data, '');
        if(!$brand->save()){
            return false;
        }else{
            return $brand->attributes['brand_id'];
        }
    }

    /**
     * 删除品牌
     * @param  [type] $brand_id [description]
     * @return [type]           [description]
     */
    public static function delete_brand($brand_id)
    {
        $result = self::deleteAll(['brand_id' => intval($brand_id)]);
        if($result){
            $goods = MallGoods::updateAll(['brand_id' => 0], 'brand_id=:brand_id', [':brand_id' => $brand_id]);
            if($goods >= 0){
                return true;
            }
        }
        return false;
    }
}
