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
use fec\helpers\CDir;
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
	public $imageFloder;
	/**
	 * upload image max size
	 */
	public $maxUploadMSize;
	/**
	 * allow image type
	 */
	public $allowImgType;
	
	/**
	 * curent max upload size 
	 */
	private $_maxUploadSize;
	/**
	 * image  absolute floder that can save product image,
	 * example:/www/web/fecshop/appadmin/web/media/catalog/product
	 */
	private $_imageBaseFloder;
	
	/**
	 * image  absolute url that  product image saved,
	 * example:http://www.fecshop.com/media/catalog/product
	 */
	private $_imageBaseUrl;
	
	/**
	 * default allowed image type ,if not set allowImgType in config file ,this value will be effective.
	 */
	private $_defaultAllowImgType = ['image/jpeg','image/gif','image/png'];
	/**
	 * default allow image upload size (MB) ,if not set maxUploadMSize in config file ,this value will be effective.
	 */
	private $_defaultMaxUploadMSize = 2; #mb
	/**
	 * default relative image save floder ,if not set imageFloder in config file ,this value will be effective.
	 */
	private $_defaultImageFloder = 'media/catalog/product';
	//private $_image
	
	
	
	/**
	 * @property $param_img_file | Array .
	 * upload image from web page , you can get image from $_FILE['XXX'] , 
	 * $param_img_file is get from $_FILE['XXX'].
	 * return , if success ,return image saved relative file path , like '/b/i/big.jpg'
	 * if fail, reutrn false;
	 */
	public function saveProductUploadImg($param_img_file){
		$this->initUploadImage();
		$size = $param_img_file['size']; 
		$file = $param_img_file['tmp_name'];
		$name = $param_img_file['name'];
		if($size > $this->_maxUploadSize){
			throw new InvalidValueException('upload image is to max than '.($this->maxUploadSize/(1024*1024)));
		}else if($img = getimagesize($file)){
			$imgType = $img['mime'];
			if(in_array($imgType,$this->allowImgType)){
				
			}else{
				throw new InvalidValueException('image type is not allow for '.$imgType);
			}
		}
		// process image name.
		$imgSavedRelativePath = $this->getImgSavedRelativePath($name);
		$isMoved = @move_uploaded_file ( $file, $this->getImageBaseFloder().$imgSavedRelativePath);
		if($isMoved){
			return $imgSavedRelativePath;
		}
		return false;
	}
	
	
	protected function resize($imgPath,$width='',$height=''){
		 
		
	}
	
	
	
	/**
	 *  init Object property. 
	 */
	
	protected function initUploadImage(){
		if(!$this->allowImgType){
			$this->allowImgType = $this->_defaultAllowImgType;
		}
		if(!$this->_maxUploadSize){
			if($this->maxUploadMSize){
				$this->_maxUploadSize = $this->maxUploadMSize * 1024 * 1024;
			}else{
				$this->_maxUploadSize = $this->_defaultMaxUploadMSize * 1024 * 1024;
			}
		}
		$this->getImageFloder();
	}
	
	/**
	 *  Get  relative Floder  that  product image saved.
	 */
	protected function getImageFloder(){
		if(!$this->imageFloder){
			$this->imageFloder = $this->_defaultImageFloder;
		}
	}
	/**
	 *  Get  absolute Floder  that  product image saved.
	 */
	protected function getImageBaseFloder(){
		if(!$this->_imageBaseFloder){
			if(!$this->imageFloder)
				$this->getImageFloder();
			$this->_imageBaseFloder = Yii::getAlias("@webroot").'/'. $this->imageFloder;
		}
		return $this->_imageBaseFloder;
	}
	
	
	/**
	 *  Get  Image base url string  that  product image saved floder.
	 */
	protected function getImageBaseUrl(){
		if(!$this->_imageBaseUrl){
			if(!$this->imageFloder)
				$this->getImageFloder();
			$this->_imageBaseUrl =  Yii::$app->homeUrl.'/'. $this->imageFloder;
		}
		return $this->_imageBaseUrl;
	}
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * get Image save file path, if floder is not exist, this function will create floder.
	 * if image file is exsit , image file name will be change  to a not existed file name( by add radom string to file name ).
	 * return image saved relative path , like /a/d/advert.jpg
	 */
	protected function getImgSavedRelativePath($name){
		list($imgName,$imgType) = explode('.',$name);
		if(!$imgName || !$imgType){
			throw new InvalidValueException('image file name and type is not correct');
		}
		if(strlen($imgName) < 2){
			$imgName .= time(). mt_rand(100, 999);
		}
		$first_str = substr($imgName,0,1);
		$two_str   = substr($imgName,1,2);
		
		$imgSaveFloder = CDir::createFloder($this->getImageBaseFloder(),[$first_str,$two_str]);
		if($imgSaveFloder){
			$imgName = $this->getUniqueImgNameInPath($imgSaveFloder,$imgName,$imgType);
			$relative_floder = '/'.$first_str.'/'.$two_str.'/';
			return $relative_floder.$imgName;
		}
		return false;
		
	}
	
	/**
	 * @property $imgSaveFloder|String image save Floder absolute Path
	 * @property $name|String , image file name ,not contain  image suffix. 
	 * @property $imageType|String , image file suffix. like '.gif','jpg' 
	 * return saved Image Name.
	 */
	
	protected function getUniqueImgNameInPath($imgSaveFloder,$name,$imageType,$randStr=''){
		$imagePath = $imgSaveFloder.'/'.$name.$randStr.'.'.$imageType;
		if(!file_exists($imagePath)){
			return $name.$randStr.'.'.$imageType;;
		}else{
			$randStr = time().rand(100,999);
			return $this->getUniqueImgNameInPath($imgSaveFloder,$name,$imageType,$randStr);
		}
	}
	
	
 
}