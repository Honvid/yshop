<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mall_suppliers".
 *
 * @property integer $suppliers_id
 * @property string $suppliers_name
 * @property string $suppliers_desc
 * @property integer $is_check
 */
class MallSuppliers extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_suppliers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['suppliers_desc'], 'string'],
            [['is_check'], 'integer'],
            [['suppliers_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'suppliers_id' => 'Suppliers ID',
            'suppliers_name' => 'Suppliers Name',
            'suppliers_desc' => 'Suppliers Desc',
            'is_check' => 'Is Check',
        ];
    }

    /**
     * 取得供应商列表
     * @return [type] [description]
     */
    public static function get_suppliers_list()
    {
        $query = self::find();
        return $query->where(['is_check' => 1])->asArray()->all();
    }

    /**
     * 查询某供货商信息
     * @param $suppliers_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function find_by_id($suppliers_id)
    {
        $query = self::find();
        return $query->where(['is_check' => 1, 'suppliers_id' => intval($suppliers_id)])->asArray()->all();
    }
}
