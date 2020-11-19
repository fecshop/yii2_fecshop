<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use fec\helpers\CDir;
use Yii;
use yii\base\InvalidValueException;

/**
 * Image services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Image extends Service
{
    /**
     * absolute image save floder.
     */
    public $imageFloder = 'media/upload';
    /**
     * upload image max size (MB).
     */
    public $maxUploadMSize = 2;
    /**
     * allow image type.
     */
    public $allowImgType = [
        'image/jpeg',
        'image/gif',
        'image/png',
        'image/jpg',
        'image/pjpeg',
    ];
    protected $_maxUploadSize;
    public $commonBaseDir;
    public $commonBaseDomain;
    
    /**
     * @param $file | string, 图片文件路径
     * @return boolean， 是否是允许的图片类型
     */
    public function isAllowImgType($file, $fileName)
    {
        $img = getimagesize($file);
        $imgType = $img['mime'];

        if (!in_array($imgType, $this->allowImgType)) {
            
            return false;
        }
        // 文件后缀检查
        $fileNameArr = explode('.', $fileName);
        $fileSuffix = $fileNameArr[count($fileNameArr)-1];
        $allowImgSuffix = $this->getAllowImgSuffix();
        if (!in_array($fileSuffix, $allowImgSuffix)) {
            
            return false;
        }
        
        return true;
    }
    public function getAllowImgSuffix()
    {
        $arr = [];
        if (!is_array($this->allowImgType) || empty($this->allowImgType)) {
            
            return [];
        }
        foreach ($this->allowImgType as $one) {
            $oneArr = explode('/',$one);
            $arr[] = $oneArr[1];
        }
        
        return $arr;
    }
    
    public function init()
    {
        parent::init();
        
        $this->commonBaseDomain = Yii::$app->store->get('base_info', 'image_domain');
    }
    /**
     * 得到logo的url
     */
    public function getLogoImgUrl()
    {
        $logoImg = Yii::$app->store->get('base_info', 'logo_image');
        if ($logoImg) {
            
            return $this->getUrlByRelativePath($logoImg);
        }
        
        return Yii::$service->image->getImgUrl('appfront/custom/logo.png');
    }
    
    /**
     * 得到logo的url
     */
    public function getFecmallLogoImgUrl()
    {
        
        return Yii::$service->image->getImgUrl('appfront/custom/logo.png');
    }

    /**
     * @param $str | String 图片的相对路径
     * @param $app | String @appimage下面的文件夹的名称。各个名称对应各个入口的名字，譬如common appfront appadmin等
     * @return 返回图片的绝对路径。
     */
    public function getImgDir($str = '')  // , $app = 'common' 第二个参数废弃
    {
        if ($str) {
            
            return Yii::getAlias($this->commonBaseDir) . '/'.$str;
        }

        return Yii::getAlias($this->commonBaseDir);
    }

    /**
     * @param $str | String 图片的相对路径
     * @param $app | String @appimage下面的文件夹的名称。各个名称对应各个入口的名字，譬如common appfront appadmin等
     * @return 返回图片的完整URL
     */ 
    public function getImgUrl($str)   // , $app = 'common' 第二个参数废弃
    {
        if ($str) {
            
            return $this->commonBaseDomain.'/'.$str;
        }

        return $this->commonBaseDomain;
    }

    /**
     * @param $app | String @appimage下面的文件夹的名称。各个名称对应各个入口的名字，譬如common appfront appadmin等
     * @return 返回图片存放目录的绝对路径。
     */
    public function getBaseImgDir($app = 'common')
    {
        return $this->getImgDir('', $app);
    }

    /**
     * @param $app | String @appimage下面的文件夹的名称。各个名称对应各个入口的名字，譬如common appfront appadmin等
     * @return 返回图片存放目录的URL
     */
    public function getBaseImgUrl($app = 'common')
    {
        return $this->getImgUrl('', $app);
    }

    /**
     * @param $uploadSize | Int , 多少MB
     * 设置上传图片的最大的size. 参数单位为MB
     */
    public function setMaxUploadSize($uploadSize)
    {
        $this->_maxUploadSize = $uploadSize * 1024 * 1024;
    }

    /**
     * 得到上传图片的最大的size.
     */
    public function getMaxUploadSize()
    {
        if (!$this->_maxUploadSize) {
            if ($this->maxUploadMSize) {
                $this->_maxUploadSize = $this->maxUploadMSize * 1024 * 1024;
            }
        }

        return $this->_maxUploadSize;
    }

    /**
     * 得到（上传）保存图片所在相对根目录的url路径.
     */
    public function getCurrentBaseImgUrl()
    {
        return $this->GetImgUrl($this->imageFloder, 'common');
    }

    /**
     * 得到（上传）保存图片所在相对根目录的文件夹路径.
     */
    public function getCurrentBaseImgDir()
    {
        return $this->GetImgDir($this->imageFloder, 'common');
    }

    /**
     * @param $str | String , 图片的相对路径字符串
     * 通过图片的相对路径得到产品图片的url.
     */
    public function getUrlByRelativePath($str)
    {
        return $this->GetImgUrl($this->imageFloder.$str, 'common');
    }

    /**
     * @param $str | String , 图片的相对路径字符串
     * 通过图片的相对路径得到产品图片的绝对路径.
     */
    public function getDirByRelativePath($str)
    {
        return $this->GetImgDir($this->imageFloder.$str, 'common');
    }

    /**
     * @param $name | String , 图片的原始名字，也就是图片上传的时候的名字。
     * @param $length | String ， 生成图片随机字符的长度。
     * 随机生成图片的新名字，因为有的图片名字可能是中文或者其他语言，而fecshop在保存名字的时候会取名字的前2个字母生成2层文件夹
     * 这样中文名字就会出现问题，因此需要使用随机生成的名字（生成2层文件夹，是为了让文件夹下面不至于太多的文件，linux文件夹下的文件超过几万个，查找文件就会有点慢，这样做是为了避免这个文件。）
     */
    protected function generateImgName($name, $length = 15)
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
     * @param $param_img_file | Array .
     * 上传产品图片，
     * 如果成功，保存产品相对路径，譬如： '/b/i/big.jpg'
     * 如果失败，reutrn false;
     */
    public function saveUploadImg($FILE)
    {
        $size = $FILE['size'];
        $file = $FILE['tmp_name'];
        $name = $FILE['name'];
        $newName = $this->generateImgName($name);
        if (!$newName) {
            throw new InvalidValueException('generate img name fail');
        }
        if ($size > $this->getMaxUploadSize()) {
            throw new InvalidValueException('upload image is to max than'. $this->getMaxUploadSize().' MB');
        } elseif (!($img = getimagesize($file))) {
            throw new InvalidValueException('file type is empty.');
        } elseif ($img = getimagesize($file)) {
            $imgType = $img['mime'];
            if (!$this->isAllowImgType($file, $name)) {
                throw new InvalidValueException('image type is not allow for '.$imgType);
            }
        }
        // process image name.
        $imgSavedRelativePath = $this->getImgSavedRelativePath($newName);
        $isMoved = @move_uploaded_file($file, $this->GetCurrentBaseImgDir().$imgSavedRelativePath);
        if ($isMoved) {
            $imgUrl = $this->getUrlByRelativePath($imgSavedRelativePath);
            $imgPath = $this->getDirByRelativePath($imgSavedRelativePath);

            return [$imgSavedRelativePath, $imgUrl, $imgPath];
        } else {
            
            return false;
        }
    }

    /**
     * get Image save file path, if floder is not exist, this function will create floder.
     * if image file is exsit , image file name will be change  to a not existed file name( by add radom string to file name ).
     * return image saved relative path , like /a/d/advert.jpg.
     */
    protected function getImgSavedRelativePath($name)
    {
        list($imgName, $imgType) = explode('.', $name);
        if (!$imgName || !$imgType) {
            throw new InvalidValueException('image file name and type is not correct');
        }
        if (strlen($imgName) < 2) {
            $imgName .= time(). mt_rand(100, 999);
        }
        $first_str = substr($imgName, 0, 1);
        $two_str = substr($imgName, 1, 2);
        $imgSaveFloder = CDir::createFloder($this->GetCurrentBaseImgDir(), [$first_str, $two_str]);
        if ($imgSaveFloder) {
            $imgName = $this->getUniqueImgNameInPath($imgSaveFloder, $imgName, $imgType);
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
    protected function getUniqueImgNameInPath($imgSaveFloder, $name, $imageType, $randStr = '')
    {
        $imagePath = $imgSaveFloder.'/'.$name.$randStr.'.'.$imageType;
        if (!file_exists($imagePath)) {
            
            return $name.$randStr.'.'.$imageType;
        } else {
            $randStr = time().rand(10000, 99999);

            return $this->getUniqueImgNameInPath($imgSaveFloder, $name, $imageType, $randStr);
        }
    }
}
