<?php

namespace common\models;

use common\helpers\RedisKey;
use Yii;

/**
 * This is the model class for table "mall_role".
 *
 * @property integer $role_id
 * @property string $role_name
 * @property string $rule_list
 * @property string $desc
 * @property integer $sort
 * @property integer $create_at
 * @property integer $update_at
 */
class MallRole extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort', 'create_at', 'update_at'], 'integer'],
            [['role_name'], 'string', 'max' => 100],
            [['rule_list'], 'string'],
            [['desc'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'role_name' => 'Role Name',
            'rule_list' => 'Rule List',
            'desc' => 'Desc',
            'sort' => 'Sort',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * 列出符合条件的角色
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
                $sorts['role_id'] = SORT_DESC;
                $query->addOrderBy($sorts);
            }

            if (!empty($filter['keys'])) {
                $query->andWhere("role_name LIKE '%{$filter['keys']}%'");
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
     * 通过角色ID获取角色信息
     * @param $role_id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function get_by_id($role_id)
    {
        return self::find()->where(['role_id' => intval($role_id)])->asArray()->one();
    }

    /**
     * 添加或者更新角色信息
     * @param $data
     * @param $rule_id
     * @return bool
     */
    public static function save_role($data, $role_id)
    {
        if (!empty($role_id)) {
            $role = self::find()->where(['role_id' => intval($role_id)])->one();
            if (empty($role)) {
                $role = new self();
                $data['create_at'] = time();
            }
        } else {
            $role = new self();
            $data['create_at'] = time();
        }
        $data['update_at'] = time();
        $role->load($data, '');
        if (!$role->save()) {
            return $role->getErrors();
        } else {
            $redis = Yii::$app->redis;
            $redis->hset(RedisKey::ROLE_RIGHTS_HASH_KEY, $role->attributes['role_id'], json_encode($role->attributes));
            return $role->attributes['role_id'];
        }
    }

    /**
     * 获取角色列表
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function get_role_list()
    {
        return self::find()->asArray()->all();
    }

    /**
     * 删除角色
     * @param $role_id
     * @return bool
     * @throws \Exception
     */
    public static function delete_role($role_id)
    {
        Yii::$app->redis->hdel(RedisKey::ROLE_RIGHTS_HASH_KEY, $role_id);
        $res = self::findOne(['role_id' => intval($role_id)])->delete();
        if($res){
            $count = MallAdminUser::updateAll(['role' => 0], ['role' => intval($role_id)]);
            if($count >= 0){
                return true;
            }
        }
        return false;
    }
}
