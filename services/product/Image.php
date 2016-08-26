<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\product;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Image extends Service
{
	/**
	 * absolute image save floder
	 */
	public $imageFloder = 'media/catalog/product';
	/**
	 * upload image max size
	 */
	public $maxUploadMSize;
	/**
	 * allow image type
	 */
	public $allowImgType = [
		'image/jpeg',
		'image/gif',
		'image/png',
		'image/jpg',
		'image/pjpeg',
	];
	
	public $defaultImg = '/default.jpg';
	public $waterImg = 'product_water.jpg';
	protected $_defaultImg;
	protected $_md5WaterImgPath;
	
	/**
	 * 得到保存产品图片所在相对根目录的url路径
	 */
	protected function actionGetBaseUrl(){
		return Yii::$service->image->GetImgUrl($this->imageFloder,'common');
	}
	/**
	 * 得到保存产品图片所在相对根目录的文件夹路径
	 */
	protected function actionGetBaseDir(){
		return Yii::$service->image->GetImgDir($this->imageFloder,'common');
	}
	/**
	 * 通过产品图片的相对路径得到产品图片的url
	 */
	protected function actionGetUrl($str){
		return Yii::$service->image->GetImgUrl($this->imageFloder.$str,'common');
	}
	/**
	 * 通过产品图片的相对路径得到产品图片的绝对路径
	 */
	protected function actionGetDir($str){
		return Yii::$service->image->GetImgDir($this->imageFloder.$str,'common');
	}
	
	
	/**
	 * @property $param_img_file | Array .
	 * upload image from web page , you can get image from $_FILE['XXX'] , 
	 * $param_img_file is get from $_FILE['XXX'].
	 * return , if success ,return image saved relative file path , like '/b/i/big.jpg'
	 * if fail, reutrn false;
	 */
	protected function actionSaveProductUploadImg($FILE){
		Yii::$service->image->imageFloder = $this->imageFloder;
		Yii::$service->image->allowImgType = $this->allowImgType;
		if($this->maxUploadMSize){
			Yii::$service->image->setMaxUploadSize($this->maxUploadMSize);
		}
		return Yii::$service->image->saveUploadImg($FILE);
	}
	
	
	protected function actionDefautImg(){
		if(!$this->_defaultImg){
			$this->_defaultImg = $this->getUrl($this->defaultImg);
		}
		return $this->_defaultImg;
	}
	
	//$resize = [111,222]
	protected function actionGetResize($imageVal,$imgResize,$isWatered=false){
		list($width,$height) = $imgResize;
		if(!$width && !$height){
			throw new InvalidValueException('resize img width and height can not empty');
		}
		if($width && !$height){
			$height = $width;
		}
		if(!$width && $height){
			$width = $height;
		}	
		$imgResize = [$width , $height];
		
		$originImgPath = $this->getDir($imageVal);
		$waterImgPath = '';
		if($isWatered){
			$waterImgPath = $this->getDir('/'.$this->waterImg);
		}
		list($newPath,$newUrl) = $this->getProductNewPath($imageVal,$imgResize,$waterImgPath);
		if(!file_exists($newPath)){
			\fec\helpers\CImage::saveResizeMiddleWaterImg($originImgPath,$newPath,$imgResize,$waterImgPath);
		}
		return $newUrl;
	}
	
	protected function getProductNewPath($imageVal,$imgResize,$waterImgPath){
		if(!$this->_md5WaterImgPath){
			if(!$waterImgPath){
				$waterImgPath = 'defaultWaterPath';
			}
			//echo $waterImgPath;exit;
			$this->_md5WaterImgPath = md5($waterImgPath);
		}
		
		$baseDir = '/cache/'.$this->_md5WaterImgPath;
		list($width,$height) = $imgResize;
		
		$imageArr = explode('/',$imageVal);
		$dirArr = ['cache',$this->_md5WaterImgPath,$width,$height];
		foreach($imageArr as $igf){
			if($igf && !strstr($igf,'.')){
				$dirArr[] = $igf;
			}
		}
		\fec\helpers\CDir::createFloder($this->getBaseDir(),$dirArr);
		$newPath 	= $this->getBaseDir().$baseDir .'/'.$width.'/'.$height.$imageVal;
		$newUrl 	= $this->getBaseUrl().$baseDir .'/'.$width.'/'.$height.$imageVal;
		return [$newPath,$newUrl];
	}
	
	
	
	
	
	
	
	
}