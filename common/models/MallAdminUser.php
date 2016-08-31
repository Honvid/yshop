<?php

namespace common\models;

use common\helpers\RedisKey;
use common\helpers\StringHelper;
use Yii;

/**
 * This is the model class for table "mall_admin_user".
 *
 * @property integer $user_id
 * @property string $user_name
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property integer $last_login
 * @property string $last_ip
 * @property string $lang_type
 * @property integer $agency_id
 * @property integer $suppliers_id
 * @property string $todolist
 * @property integer $role
 * @property integer $is_admin
 * @property string $auth_key
 * @property integer $create_at
 * @property integer $update_at
 */
class MallAdminUser extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_admin_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'last_login', 'agency_id', 'suppliers_id', 'role', 'is_admin', 'create_at', 'update_at'], 'integer'],
            [['todolist'], 'string'],
            [['user_name', 'email'], 'string', 'max' => 120],
            [['password', 'auth_key'], 'string', 'max' => 32],
            [['salt'], 'string', 'max' => 30],
            [['last_ip'], 'string', 'max' => 20],
            [['lang_type'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'email' => 'Email',
            'password' => 'Password',
            'salt' => 'Salt',
            'last_login' => 'Last Login',
            'last_ip' => 'Last Ip',
            'lang_type' => 'Lang Type',
            'agency_id' => 'Agency ID',
            'suppliers_id' => 'Suppliers ID',
            'todolist' => 'Todolist',
            'role' => 'Role',
            'auth_key' => '自动登录key',
            'is_admin' => '超级管理员标志',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * 列出符合条件的用户
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
                $sorts['user_id'] = SORT_DESC;
                $query->addOrderBy($sorts);
            }

            if (!empty($filter['keys'])) {
                $query->andWhere("user_name LIKE '%{$filter['keys']}%'");
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
        $list = $command->queryAll();
        foreach($list as &$l){
            $redis = Yii::$app->redis;
            $role = json_decode($redis->hget(RedisKey::ROLE_RIGHTS_HASH_KEY, $l['role']),true);
            if(!empty($role)){
                $l['role_name'] = $role['role_name'];
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
     * 登陆验证
     * @param $name
     * @param $password
     * @param $is_remember
     * @return bool
     */
    public static function check_login($name, $password, $is_remember = 0)
    {
        $check = self::find()->where(['user_name' => $name])->orWhere(['email' => $name])->asArray()->one();
        if(empty($check)){
            return false;
        }
        $query = self::find()->where([
            'user_name' => $name,
            'password'  => self::encrypt_password($password, $check['salt']),
            'salt'   => $check['salt'],
        ])->orWhere([
            'email' => $name,
            'password'  => self::encrypt_password($password, $check['salt']),
            'salt'   => $check['salt'],
        ])->one();
        if(!empty($query)){
            // 检查是否为供货商的管理员 所属供货商是否有效
            if(!empty($query->suppliers_id)){
                $supplier_is_check = MallSuppliers::find_by_id($query->suppliers_id);
                if(empty($supplier_is_check)){
                    return false;
                }
            }
            $query->last_login = time();
            $query->last_ip = StringHelper::get_real_ip();
            if($query->save()) {
                $session = Yii::$app->session;
                if(!$session->getIsActive()){
                    $session->open();
                }
                $session->set('admin_id', $query->user_id);
                $session->set('admin_name', $query->user_name);
                $session->set('suppliers_id', $query->suppliers_id);
                $session->set('is_admin', $query->is_admin);
                if($is_remember == 1){
                    $cookies = Yii::$app->response->cookies;
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'admin_id',
                        'value' => $query->user_id,
                        'expire' => time() + 3600,
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'admin_pass',
                        'value' => strtoupper(md5($query->password)),
                        'expire' => time() + 3600,
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'is_admin',
                        'value' => $query->is_admin,
                        'expire' => time() + 3600,
                    ]));
                }
                return true;
            }
        }
        return false;
    }

    /**
     * 登陆状态判断
     * @param $user_id
     * @param $password
     * @return bool
     */
    public static function is_login($user_id, $password)
    {
        $check = self::find()->where(['user_id' => intval($user_id)])->asArray()->one();
        if(empty($check)){
            return false;
        }
        if($password == strtoupper(md5($check['password']))){
            $session = Yii::$app->session;
            if(!$session->getIsActive()){
                $session->open();
            }
            $session->set('admin_id', $check['user_id']);
            $session->set('admin_name', $check['user_name']);
            $session->set('suppliers_id', $check['suppliers_id']);
            return true;
        }
        return false;
    }

    /**
     * 加密或解密密码
     * @param $password
     * @param string $salt
     * @return array|string
     */
    protected static function encrypt_password($password, $salt = '')
    {
        if(!empty($salt)){
            return md5(md5($password).$salt);
        }
        $new_salt = date('Yis');
        return ['password' => md5(md5($password).$new_salt), 'salt' => $new_salt ];
    }

    /**
     * 创建或者更新用户
     * @param $data
     * @param $user_id
     * @return bool
     */
    public static function save_user($data, $user_id)
    {
        if (!empty($user_id)) {
            $user = self::find()->where(['user_id' => intval($user_id)])->one();
            if (empty($user)) {
                $user = new self();
                $data['create_at'] = time();
            }
        } else {
            $user = new self();
            $data['create_at'] = time();
        }
        if(!empty($data['password'])){
            $encrypt = self::encrypt_password($data['password']);
            $data['password'] = $encrypt['password'];
            $data['salt'] = $encrypt['salt'];
        }else{
            unset($data['password']);
        }
        $user->load($data, '');
        if (!$user->save()) {
            return false;
        } else {
            $redis = Yii::$app->redis;
            $redis->hset(RedisKey::ADMIN_USER_HASH_KEY, $user->attributes['user_id'], json_encode($user->attributes));
            return $user->attributes['user_id'];
        }
    }

    /**
     * 获取用户详情
     * @param $user_id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function get_by_id($user_id)
    {
        $query = self::find();
        return  $query->where(['user_id' => intval($user_id)])->asArray()->one();
    }

    /**
     * 获取用户的权限集合
     * @param $user_id
     * @return array|mixed
     */
    public static function user_rights($user_id)
    {
        $redis = Yii::$app->redis;
        if($redis->hexists(RedisKey::ADMIN_USER_HASH_KEY, $user_id)){
            $user = json_decode($redis->hget(RedisKey::ADMIN_USER_HASH_KEY, $user_id), true);
        }
        if(empty($user)) {
            $user = self::get_by_id($user_id);
            if(empty($user)) {
                return [];
            }
            $redis->hset(RedisKey::ADMIN_USER_HASH_KEY, $user_id, json_encode($user));
        }
        if(empty($user['role'])){
            return [];
        }
        if($redis->hexists(RedisKey::ROLE_RIGHTS_HASH_KEY, $user['role'])){
            $rights = json_decode($redis->hget(RedisKey::ROLE_RIGHTS_HASH_KEY, $user['role']), true);
        }
        if(empty($rights)){
            $rights = MallRole::get_by_id($user['role']);
            if(empty($rights)){
                return [];
            }
            $redis->hset(RedisKey::ROLE_RIGHTS_HASH_KEY, $user['role'], json_encode($rights));
        }
        return json_decode($rights['rule_list'], true);
    }

    /**
     * 删除管理员
     * @param $user_id
     * @return false|int
     * @throws \Exception
     */
    public static function delete_user($user_id)
    {
        Yii::$app->redis->hdel(RedisKey::ADMIN_USER_HASH_KEY, $user_id);
        return self::findOne(['user_id' => intval($user_id)])->delete();
    }
}
