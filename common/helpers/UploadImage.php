<?php
namespace common\helpers;

use Yii;

/**
 * 图片上传类
 * @author whxbb(whxbb@21cn.com)
 * @version 0.1
 */
class UploadImage
{
    protected static $file;
    protected static $file_name;
    protected static $file_size;

    protected static $savename; // 保存名
    protected static $savepath = 'web/upload'; // 保存路径
    protected static $fileformat = [
        'png',
        'jpg',
        'gif',
        'jpeg',
        'bmp',
    ]; // 文件格式限定
    protected static $overwrite = 0; // 覆盖模式 0 不允许， 1 允许
    protected static $maxsize = 1024000; // 文件最大字节
    protected static $ext; // 文件扩展名
    protected static $errno = 0; // 错误代号
    protected static $is_ck = true; // 是否为CKeditor
    protected static $UPLOAD_CLASS_ERROR = [
        1 => '不允许上传该格式文件',
        2 => '目录不可写',
        3 => '文件已存在',
        4 => '未知错误',
        5 => '文件太大',
        6 => '请添加上传文件',
    ];

    /**
     * 构造函数
     * @param overwriet 是否覆盖 1 允许覆盖 0 禁止覆盖
     */
    public static function upload($path, $is_ck = true, $saveName = '', $savepath = '', $overwrite = 1)
    {
        if(empty($_FILES[$path])){
            self::$errno = 6;
            return false;
        }
        self::$is_ck = $is_ck;
        self::set_overwrite($overwrite);
        self::set_savepath($savepath); // 设置保存路径
        /** 检查目录是否可写 */
        if(!@is_writable(self::$savepath)){
            self::$errno = 2;
            return false;
        }
        self::set_savepath(self::$savepath . date('Y-m-d'));
        if ( !is_dir(self::$savepath) ){
            mkdir(self::$savepath, 0777, true);
        }
        if(is_array($_FILES[$path]['tmp_name'])){
            $temp = [];
            foreach ($_FILES[$path]['tmp_name'] as $key => $value) {
                self::$file = $value;
                self::$file_name = $_FILES[$path]['name'][$key];
                self::$file_size = $_FILES[$path]['size'][$key];
                self::get_ext();
                self::set_savename($saveName); // 设置保存文件名称
                /** 检查文件格式 */
                if (!self::validate_format()){
                    self::$errno = 1;
                    return false;
                }

                /** 如果不允许覆盖，检查文件是否已经存在 */
                if(self::$overwrite == 0 && @file_exists(self::$savepath . DS .self::$savename)){
                    self::$errno = 3;
                    return false;
                }
                // 文件大小判断
                if (self::$file_size > self::$maxsize){
                    self::$errno = 5;
                    return false;
                }
                /** 文件上传 */
                if(!copy(self::$file, self::$savepath . DS . self::$savename)){
                    self::$errno = 4;
                    return false;
                }
                /** 删除临时文件 */
                self::destory();
                $temp[$key] = "/upload/" . date('Y-m-d') . "/" . self::$savename;
            }
            return $temp;
        }else{
            self::$file = $_FILES[$path]['tmp_name'];
            self::$file_name = $_FILES[$path]['name'];
            self::$file_size = $_FILES[$path]['size'];

            self::get_ext();
            self::set_savename($saveName); // 设置保存文件名称

            /** 检查文件格式 */
            if (!self::validate_format()){
                self::$errno = 1;
                return false;
            }

            /** 如果不允许覆盖，检查文件是否已经存在 */
            if(self::$overwrite == 0 && @file_exists(self::$savepath . DS .self::$savename)){
                self::$errno = 3;
                return false;
            }
            // 文件大小判断
            if (self::$file_size > self::$maxsize){
                self::$errno = 5;
                return false;
            }
            /** 文件上传 */
            if(!copy(self::$file, self::$savepath . DS . self::$savename)){
                self::$errno = 4;
                return false;
            }
            /** 删除临时文件 */
            self::destory();  
            return "/upload/" . date('Y-m-d') . "/" . self::$savename;
        }
    }

    public static function set_savepath($path)
    {
        if(empty($path)){
            self::$savepath = APP_PATH . self::$savepath . DS;
        }else{
            self::$savepath = $path;
        }
    }

    /**
     * 文件格式检查
     * @return bool
     */
    protected static function validate_format()
    {
        if(!is_array(self::$fileformat) || in_array(strtolower(self::$ext), self::$fileformat)){
            return true;
        }
        return false;
    }

    /**
     * 获取文件扩展名
     * access public
     */
    public static function get_ext()
    {
        $ext = explode(".", self::$file_name);
        self::$ext = $ext[count($ext) - 1];
    }

    /**
     * 设置覆盖模式
     * @param 覆盖模式 1:允许覆盖 0:禁止覆盖
     * @access public
     */
    public static function set_overwrite($overwrite)
    {
        self::$overwrite = $overwrite;
    }

    /**
     * 设置文件保存名
     * @savename 保存名，如果为空，则系统自动生成一个随机的文件名
     * @access public
     */
    public static function set_savename($savename)
    {
        if ($savename == '')  // 如果未设置文件名，则生成一个随机文件名
        {
            $name = date('Ymdhis').rand(10000, 99999) . "." . self::$ext;
        } else {
            $name = $savename . "." . self::$ext;
        }
        self::$savename = $name;
    }
    /**
     * 删除文件
     * @param $file 所要删除的文件名
     * @access public
     */
    public static function del($file)
    {
        if(!@unlink($file))
        {
            self::$errno = 3;
            return false;
        }
        return true;
    }
    /**
     * 删除临时文件
     * @access 
     */
    public static function destory()
    {
        self::del(self::$file);
    }

    /**
     * 取得错误码
     * @return [type]       [description]
     */
    public static function errmsg()
    {
        if (self::$errno != 0){
            return self::$UPLOAD_CLASS_ERROR[self::$errno];
        }
    }

    /**
     * 与CKeditor通信
     * @param  string $path    [description]
     * @return [type]          [description]
     */
    public static function format_for_ck_editor($path)
    {
        if(empty($path) || $path === false){
            $path = '';
        }
        if(self::$errno == 0){
            $msg = '';
        }else{
            $msg = self::$UPLOAD_CLASS_ERROR[self::$errno];
        }
        $callback = $_GET["CKEditorFuncNum"];  
        $back = "<script type='text/javascript'>
                    window.parent.CKEDITOR.tools.callFunction(
                        '" . $callback . "',
                        '" . $path . "',
                        '" . $msg . "')
                 </script>";
        return $back;
    }
}
?>