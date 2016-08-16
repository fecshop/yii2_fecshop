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
	
	protected $_maxUploadSize;
	/**
	 * 得到上传图片的最大的size
	 */
	protected function actionGetMaxUploadSize(){
		if(!$this->_maxUploadSize){
			if($this->maxUploadMSize){
				$this->_maxUploadSize = $this->maxUploadMSize * 1024 * 1024;
			}
		}
		return $this->_maxUploadSize;
	} 
	/**
	 * 得到保存产品图片所在相对根目录的url路径
	 */
	protected function actionGetBaseUrl(){
		return Yii::$service->image->GetImgUrl($this->imageFloder,'common');
	}
	/**
	 * 得到保存产品图片所在相对根目录的文件夹路径
	 */
	protected function actionGetBaseFloder(){
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
	protected function actionGetFilePath(){
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
		
		$size = $FILE['size']; 
		$file = $FILE['tmp_name'];
		$name = $FILE['name'];
		if($size > $this->getMaxUploadSize()){
			throw new InvalidValueException('upload image is to max than'. $this->maxUploadMSize.' MB');
		}else if(!($img = getimagesize($file))){
			throw new InvalidValueException('file type is empty.');
			
		}else if($img = getimagesize($file)){
			$imgType = $img['mime'];
			
			if(!in_array($imgType,$this->allowImgType)){
				throw new InvalidValueException('image type is not allow for '.$imgType);
			}
		}
		// process image name.
		$imgSavedRelativePath = $this->getImgSavedRelativePath($name);
		$isMoved = @move_uploaded_file ( $file, $this->getBaseFloder().$imgSavedRelativePath);
		if($isMoved){
			$imgUrl = $this->getUrl($imgSavedRelativePath);
			$imgPath = $this->getFilePath($imgSavedRelativePath);
			return [$imgSavedRelativePath,$imgUrl,$imgPath];
		}
		return false;
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
		
		$imgSaveFloder = CDir::createFloder($this->getBaseFloder(),[$first_str,$two_str]);
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
			$randStr = time().rand(10000,99999);
			return $this->getUniqueImgNameInPath($imgSaveFloder,$name,$imageType,$randStr);
		}
	}
	
	
 
}