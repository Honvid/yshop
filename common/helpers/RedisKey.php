<?php
/**
 * Created by PhpStorm.
 * User: Honvid
 * Date: 16/5/11
 * Time: 下午8:35
 */

namespace common\helpers;


class RedisKey
{
    const ADMIN_USER_HASH_KEY = 'admin_user_hash_key'; // 后台用户集合
    const ROLE_RIGHTS_HASH_KEY = 'role_rights_hash'; // 角色权限集合
    const ADMIN_USER_RIGHTS_HASH_KEY = 'admin_user_rights_hash_key'; // 后台用户权限集合

    const ADMIN_MENU_LIST = 'admin_menu_list'; // 后台菜单
    const AREA_LIST = 'area_list'; // 区域缓存
}