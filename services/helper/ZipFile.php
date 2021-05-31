<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\helper;

use fecshop\services\Service;
use fec\helpers\CDir;
use Yii;
use yii\base\InvalidValueException;

/**
 * Format services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
// use \fecshop\services\helper\Format;
class ZipFile extends Service
{
    public $baseZipDir = '@appimage/zip_upload';
    // MB  上传应用压缩包的最大MB数
    public $maxZipUploadMSize = 20 ;
    protected $_maxZipUploadSize;
    
    /**
     * @param $src_file | string, zip文件的完整路径
     * @param $dest_dir | bool or string，zip文件解压后的文件路径。
     * @param $create_zip_name_dir | bool or string，当$dest_dir为false的时候有效，解压到zip文件路径下。
     * @param $overwrite | bool，解压是否强制覆盖。
     * 将zip文件进行解压。
     */
    public function unzip($src_file, $dest_dir=false, $create_zip_name_dir=true, $overwrite=true)
    {
        if ($dest_dir) {
            $dest_dir .= '/';
        }
        if ($zip = zip_open($src_file)) {
            if ($zip) {
                $splitter = ($create_zip_name_dir === true) ? "." : "/";
                if ($dest_dir === false) {
                    $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter))."/";
                }
                // 如果不存在 创建目标解压目录
                $this->create_dirs($dest_dir);
                // 对每个文件进行解压
                while ($zip_entry = zip_read($zip)) {
                    // 文件不在根目录
                    $pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
                    if ($pos_last_slash !== false) {
                        // 创建目录 在末尾带
                        $this->create_dirs($dest_dir.substr(zip_entry_name($zip_entry), 0, $pos_last_slash+1));
                    }
                    // 打开包
                    if (zip_entry_open($zip,$zip_entry,"r")) {
                        // 文件名保存在磁盘上
                        $file_name = $dest_dir.zip_entry_name($zip_entry);
                        // 检查文件是否需要重写
                        if ($overwrite === true || $overwrite === false && !is_file($file_name)) {
                            // 读取压缩文件的内容
                            $fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                            @file_put_contents($file_name, $fstream);
                            // 设置权限
                            chmod($file_name, 0777);
                        }
                        // 关闭入口
                        zip_entry_close($zip_entry);
                    }
                }
                // 关闭压缩包
                zip_close($zip);
            }
        }else{
            
            return false;
        }
        
        return true;
    }
    
    /**
     * @param $path | string
     * 创建目录
     */
    protected function create_dirs($path)
    {
        if (!is_dir($path)) {
            $directory_path = "";
            $directories = explode("/",$path);
            array_pop($directories);
            foreach ($directories as $directory) {
                $directory_path .= $directory."/";
                if (!is_dir($directory_path)) {
                    mkdir($directory_path);
                    chmod($directory_path, 0777);
                }
            }
        }
    }
    /**
     * @param $name | string, 生成zip 文件
     * @param $length | int，zip文件名称长度
     *  生成随机的zip文件名称
     */
    protected function generateZipName($name, $length = 20)
    {
        $arr = explode('.', $name);
        $fileType = '.'.$arr[count($arr)-1];
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $str ='';
        for ($i = 0; $i < $length; $i++) {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $str .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);
            $str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        $str .= time();
        return $str.$fileType;
    }
    
    /**
     * 得到上传图片的最大的size.
     */
    protected function getZipMaxUploadSize()
    {
        if (!$this->_maxZipUploadSize) {
            if ($this->maxZipUploadMSize) {
                $this->_maxZipUploadSize = $this->maxZipUploadMSize * 1024 * 1024;
            }
        }

        return $this->_maxZipUploadSize;
    }
    
    /**
     * 得到（上传）保存图片所在相对根目录的文件夹路径.
     */
    public function getCurrentBaseZipDir()
    {
        return Yii::getAlias($this->baseZipDir);
    }
    /**
     * 得到（上传）保存图片所在相对根目录的文件夹路径.
     */
    public function getZipDir($relativeFilePath)
    {
        return Yii::getAlias($this->baseZipDir.$relativeFilePath);
    }
    /**
     * 
     */
    public function getZipSavedRelativePath($name)
    {
        list($imgName, $imgType) = explode('.', $name);
        if (!$imgName || !$imgType) {
            throw new InvalidValueException('zip file name and type is not correct');
        }
        if (strlen($imgName) < 2) {
            $imgName .= time(). mt_rand(100, 999);
        }
        $first_str = substr($imgName, 0, 1);
        $two_str = substr($imgName, 1, 2);

        $zipSaveFloder = CDir::createFloder($this->getCurrentBaseZipDir(), [$first_str, $two_str]);
        if ($zipSaveFloder) {
            $imgName = $this->getUniqueZipNameInPath($zipSaveFloder, $imgName, $imgType);
            $relative_floder = '/'.$first_str.'/'.$two_str.'/';

            return $relative_floder.$imgName;
        }

        return false;
    }
    
    /**
     * @param $imgSaveFloder|string image save Floder absolute Path
     * @param $name|string , image file name ,not contain  image suffix.
     * @param $imageType|string , image file suffix. like '.gif','jpg'
     * return saved Image Name.
     * 得到产品保存的唯一路径，因为可能存在名字重复的问题，因此使用该函数确保图片路径唯一。
     */
    protected function getUniqueZipNameInPath($imgSaveFloder, $name, $imageType, $randStr = '')
    {
        $imagePath = $imgSaveFloder.'/'.$name.$randStr.'.'.$imageType;
        if (!file_exists($imagePath)) {
            return $name.$randStr.'.'.$imageType;
        } else {
            $randStr = time().rand(10000, 99999);

            return $this->getUniqueZipNameInPath($imgSaveFloder, $name, $imageType, $randStr);
        }
    }
    /**
     * @param $param_img_file | Array .
     * 上传zip文件，
     * 如果成功，保存产品相对路径，譬如： '/b/i/big.jpg'
     * 如果失败，reutrn false;
     */
    public function saveUploadZip($FILE)
    {
        //var_dump($FILE);
        $size = $FILE['size'];
        $file = $FILE['tmp_name'];
        $name = $FILE['name'];
        // zip 后缀判断
        $arr = explode('.', $name);
        $fileType = $arr[count($arr)-1];
        if ($fileType != 'zip') {
            throw new InvalidValueException('file type is not zip');
        }
        if ($size > $this->getZipMaxUploadSize()) {
            
            throw new InvalidValueException('upload zip is to max than'. $this->getMaxUploadSize().' MB');
        }
        $name = $this->generateZipName($name);
        // process image name.
        $zipSavedRelativePath = $this->getZipSavedRelativePath($name);
        
        $moveFilePath = $this->getCurrentBaseZipDir().$zipSavedRelativePath;
        // 上传
        $isMoved = @move_uploaded_file($file, $moveFilePath);
        if ($isMoved) {
            
            return $zipSavedRelativePath;
        } else {
            
            return false;
        }
    }
    
    public function getDownloadfile($zipFile)
    {
        // 设置2000秒的下载时间，
        ini_set("max_execution_time", "2000");
        set_time_limit(2000);
        
        $s = explode('/', $zipFile);
        $filename = $s[count($s) - 1];
        $filePath = $this->getZipDir($zipFile);
        
        $file_fullpath = $filePath;
        header("Content-Type: application/octet-stream");
        header('Content-Disposition: attachment; filename="'.$filename.'"');//文件描述，页面下载用的文件名，可以实现用不同的文件名下载同一个文件
        
        $data = fopen($file_fullpath, 'rb');
        while (!feof($data)) {
                echo @fread($data, 8192);
                flush();
                ob_flush();
        }
        fclose($data);
    }
    
}













