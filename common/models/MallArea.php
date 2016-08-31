<?php

namespace common\models;

use common\helpers\ArrayHandle;
use common\helpers\RedisKey;
use Yii;

/**
 * This is the model class for table "mall_area".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $short_name
 * @property string $longitude
 * @property string $latitude
 * @property integer $level
 * @property integer $sort
 * @property integer $status
 */
class MallArea extends BaseModel
{
    const SHOW_STATUS_ACTIVE = 1;
    const SHOW_STATUS_DISABLED = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'short_name', 'level'], 'required'],
            [['id', 'parent_id', 'level', 'sort', 'status'], 'integer'],
            [['longitude', 'latitude'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['short_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => '父级ID',
            'name' => '名称',
            'short_name' => '简称',
            'longitude' => '经度',
            'latitude' => '纬度',
            'level' => '等级(1省/直辖市,2地级市,3区县,4镇/街道)',
            'sort' => '排序',
            'status' => '状态(0禁用/1启用)',
        ];
    }

    /**
     * 查询所有的Area信息
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function get_area_list()
    {
        $query = self::find();
        $query->select('r.*, COUNT(s.id) AS has_children');
        $query->from('mall_area AS r')->leftJoin('mall_area AS s', 's.parent_id=r.id');
        $area = $query->groupBy('r.id')->orderBy('r.parent_id ASC, r.sort ASC')->asArray()->all();
        Yii::$app->redis->set(RedisKey::AREA_LIST, json_encode($area));
        return $area;
    }

    public static function get_parent_list()
    {
        return self::find()->where(['parent_id' => 0])->orderBy('sort ASC')->asArray()->all();
    }

    /**
     * 查询子节点
     * @param $parent_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function find_children($parent_id)
    {
        return self::find()->where(['parent_id' => intval($parent_id)])->asArray()->all();
    }

    /**
     * 更新禁用状态
     * @param $id
     * @param $status
     * @return bool
     */
    public static function change_show($id, $status)
    {
        $area = self::findOne(['id' => intval($id)]);
        if(!empty($area)){
            if(intval($status) == self::SHOW_STATUS_ACTIVE) {
                $area->status = self::SHOW_STATUS_DISABLED;
            }else{
                $area->status = self::SHOW_STATUS_ACTIVE;
            }
            return $area->save();
        }
        return false;
    }

    /**
     * 通过区域ID获取详情
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function get_by_id($id)
    {
        return self::find()->where(['id' => intval($id)])->asArray()->one();
    }

    /**
     * 删除区域
     * @param $id
     * @return bool|false|int
     * @throws \Exception
     */
    public static function delete_by_id($id)
    {
        $children = self::find()->where(['parent_id' => intval($id)])->count();
        if($children > 0){
            return false;
        }else{
            return self::findOne(['id' => intval($id)])->delete();
        }
    }
}
