<?php

namespace common\helpers;

/**
* 
*/
class StringHelper
{
    /**
     * 全角数字转半角
     * @param [type] $fnum [description]
     */
    public static function get_alab_num($fnum)
    {
        $nums = ["０","１","２","３","４","５","６","７","８","９"];
        $fnums = "0123456789";

        for ($i = 0; $i <= 9; $i++){
            $fnum = str_replace($nums[$i], $fnums[$i], $fnum);
        }

        $fnum = preg_replace("/[^0-9.]|^0{1,}/", "", $fnum);

        if ($fnum == ""){
            $fnum = 0;
        }
        return $fnum;
    }

    /**
     * 字符串加密函数
     * @param    String  $salt           加盐
     * @param    String  $password       加密的字符串
     * @param    Bool  $decrypt        false表示加密，true表示解密
     * @return   String
     */
    public static function encrypt_decrypt($salt, $password, $decrypt = false)
    { 
        if($decrypt){ 
            $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($salt), base64_decode($password), MCRYPT_MODE_CBC, md5(md5($salt))), "12"); 
            return $decrypted; 
        }else{ 
            $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($salt),
            $password, MCRYPT_MODE_CBC, md5(md5($salt)))); 
            return $encrypted; 
        } 
    }

    /**
     * 生成唯一订单号
     */
    public static function get_order_sn()
    {
        return date('Ymdhis') . str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);
    }

    /**
     * 获取真实IP地址
     * @return string
     */
    public static function get_real_ip()
    {
        $real_ip = '0.0.0.0';
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr AS $ip) {
                    $ip = trim($ip);

                    if ($ip != 'unknown') {
                        $real_ip = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $real_ip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $real_ip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $real_ip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $real_ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $real_ip = getenv('HTTP_CLIENT_IP');
            } else {
                $real_ip = getenv('REMOTE_ADDR');
            }
        }

        preg_match("/[\d\.]{7,15}/", $real_ip, $online_ip);
        $real_ip = !empty($online_ip[0]) ? $online_ip[0] : '0.0.0.0';

        return $real_ip;
    }
}