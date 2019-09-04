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
use Yii;

/**
 * Format services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
// use \fecshop\services\helper\Format;
class ZipFile extends Service
{
    public function unzip($src_file, $dest_dir=false, $create_zip_name_dir=true, $overwrite=true){
        if ($dest_dir) {
            $dest_dir .= '/';
        }
        if ($zip = zip_open($src_file)){
            if ($zip){
                $splitter = ($create_zip_name_dir === true) ? "." : "/";
                if($dest_dir === false){
                    $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter))."/";
                }
                // 如果不存在 创建目标解压目录
                $this->create_dirs($dest_dir);
                // 对每个文件进行解压
                while ($zip_entry = zip_read($zip)){
                    // 文件不在根目录
                    $pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
                    if ($pos_last_slash !== false){
                        // 创建目录 在末尾带
                        $this->create_dirs($dest_dir.substr(zip_entry_name($zip_entry), 0, $pos_last_slash+1));
                    }
                    // 打开包
                    if (zip_entry_open($zip,$zip_entry,"r")){
                        // 文件名保存在磁盘上
                        $file_name = $dest_dir.zip_entry_name($zip_entry);
                        // 检查文件是否需要重写
                        if ($overwrite === true || $overwrite === false && !is_file($file_name)){
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
     * 创建目录
     */
    public function create_dirs($path){
        if (!is_dir($path)){
            $directory_path = "";
            $directories = explode("/",$path);
            array_pop($directories);
            foreach($directories as $directory){
                $directory_path .= $directory."/";
                if (!is_dir($directory_path)){
                    mkdir($directory_path);
                    chmod($directory_path, 0777);
                }
            }
        }
    }
}













