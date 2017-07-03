<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product;

use fecshop\services\Service;
use Yii;
use yii\base\InvalidValueException;

/**
 * Product Image Services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Image extends Service
{
    /**
     * absolute image save floder.
     */
    public $imageFloder = 'media/catalog/product';
    /**
     * upload image max size.
     */
    public $maxUploadMSize;
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
    // 默认产品图片，当产品图片找不到的时候，就会使用该默认图片
    public $defaultImg = '/default.jpg';
    // 产品水印图片。
    public $waterImg   = 'product_water.jpg';
    protected $_defaultImg;
    protected $_md5WaterImgPath;

    /**
     * 得到保存产品图片所在相对根目录的url路径.
     */
    protected function actionGetBaseUrl()
    {
        return Yii::$service->image->GetImgUrl($this->imageFloder, 'common');
    }

    /**
     * 得到保存产品图片所在相对根目录的文件夹路径.
     */
    protected function actionGetBaseDir()
    {
        return Yii::$service->image->GetImgDir($this->imageFloder, 'common');
    }

    /**
     * 通过产品图片的相对路径得到产品图片的url.
     */
    protected function actionGetUrl($str)
    {
        return Yii::$service->image->GetImgUrl($this->imageFloder.$str, 'common');
    }

    /**
     * 通过产品图片的相对路径得到产品图片的绝对路径.
     */
    protected function actionGetDir($str)
    {
        return Yii::$service->image->GetImgDir($this->imageFloder.$str, 'common');
    }

    /**
     * @property $param_img_file | Array .
     * upload image from web page , you can get image from $_FILE['XXX'] ,
     * $param_img_file is get from $_FILE['XXX'].
     * return , if success ,return image saved relative file path , like '/b/i/big.jpg'
     * if fail, reutrn false;
     */
    protected function actionSaveProductUploadImg($FILE)
    {
        Yii::$service->image->imageFloder = $this->imageFloder;
        Yii::$service->image->allowImgType = $this->allowImgType;
        if ($this->maxUploadMSize) {
            Yii::$service->image->setMaxUploadSize($this->maxUploadMSize);
        }

        return Yii::$service->image->saveUploadImg($FILE);
    }
    /**
     * 获取产品默认图片的完整URL
     */
    protected function actionDefautImg()
    {
        if (!$this->_defaultImg) {
            $this->_defaultImg = $this->getUrl($this->defaultImg);
        }

        return $this->_defaultImg;
    }

    /**
     * @property $imageVal | String ，图片相对路径字符串。
     * @property $imgResize | Array or Int ， 数组 [230,230] 代表生成的图片为230*230，如果宽度或者高度不够，则会用白色填充
     *                  如果 $imgResize设置为 230， 则宽度不变，高度按照原始图的比例计算出来。
     * @property $isWatered | Boolean ， 产品图片是否打水印。
     * 获取相应尺寸的产品图片。
     */
    protected function actionGetResize($imageVal, $imgResize, $isWatered = false)
    {
        
        $originImgPath = $this->getDir($imageVal);
        if (!file_exists($originImgPath)) {
            $originImgPath = $this->getDir($this->defaultImg);
        }
        $waterImgPath = '';
        if ($isWatered) {
            $waterImgPath = $this->getDir('/'.$this->waterImg);
        }
        list($newPath, $newUrl) = $this->getProductNewPath($imageVal, $imgResize, $waterImgPath);
        if (!file_exists($newPath)) {
            \fec\helpers\CImage::saveResizeMiddleWaterImg($originImgPath, $newPath, $imgResize, $waterImgPath);
        }

        return $newUrl;
    }
    /**
     * @property $imageVal | String ，图片相对路径字符串。
     * @property $imgResize | Array or Int ， 数组 [230,230] 代表生成的图片为230*230，如果宽度或者高度不够，则会用白色填充
     *                  如果 $imgResize设置为 230， 则宽度不变，高度按照原始图的比例计算出来。
     * @property $waterImgPath | String ， 水印图片的路径
     * 获取按照自定义尺寸获取的产品图片的文件绝对路径和完整url
     */
    protected function getProductNewPath($imageVal, $imgResize, $waterImgPath)
    {
        if (!$this->_md5WaterImgPath) {
            if (!$waterImgPath) {
                $waterImgPath = 'defaultWaterPath';
            }
            //echo $waterImgPath;exit;
            $this->_md5WaterImgPath = md5($waterImgPath);
        }

        $baseDir = '/cache/'.$this->_md5WaterImgPath;
        if (is_array($imgResize)) {
            list($width, $height) = $imgResize;
        } else {
            $width = $imgResize;
            $height = '0';
        }

        $imageArr = explode('/', $imageVal);
        $dirArr = ['cache', $this->_md5WaterImgPath, $width, $height];
        foreach ($imageArr as $igf) {
            if ($igf && !strstr($igf, '.')) {
                $dirArr[] = $igf;
            }
        }
        \fec\helpers\CDir::createFloder($this->getBaseDir(), $dirArr);
        $newPath = $this->getBaseDir().$baseDir .'/'.$width.'/'.$height.$imageVal;
        $newUrl = $this->getBaseUrl().$baseDir .'/'.$width.'/'.$height.$imageVal;

        return [$newPath, $newUrl];
    }
}
