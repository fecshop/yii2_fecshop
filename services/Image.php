<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
use fec\helpers\CDir;
/**
 * Image services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Image extends Service
{
	
	/**
	 * absolute image save floder
	 */
	public $imageFloder = 'media/upload';
	/**
	 * upload image max size (MB)
	 */
	public $maxUploadMSize = 2;
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
	public $appbase;
	/**
	 *  1.1 app front image  Dir
	 */
	protected function actionGetImgDir($str='',$app='common'){
		if($appbase = $this->appbase){
			if(isset($appbase[$app]['basedir'])){
				if($str){
					return Yii::getAlias($appbase[$app]['basedir'].'/'.$str);
				}
				return Yii::getAlias($appbase[$app]['basedir']);
			}
		}
	}
	/**
	 *  1.2 app front image  Url* 
	 *  example : <?= Yii::$service->image->getImgUrl('custom/logo.png','appfront'); ?>
	 *  it will find image in @appimage/$app	
	 */
	protected function actionGetImgUrl($str,$app='common'){
		//echo "$str,$app";
		if($appbase = $this->appbase){
			if(isset($appbase[$app]['basedomain'])){
				if($str){
					return $appbase[$app]['basedomain'].'/'.$str;
				}
				return $appbase[$app]['basedomain'];
			}
		}
		return ;
	}
	/**
	 *  2.1 app front image base dir
	 */
	protected function actionGetBaseImgDir($app='common'){
		return $this->getImgDir('',$app);
	}
	/**
	 *  2.2 app front image base Url
	 */
	protected function actionGetBaseImgUrl($app='common'){
		return $this->getImgUrl('',$app);
	}
	
	/**
	 * 设置上传图片的最大的size
	 */
	protected function actionSetMaxUploadSize($uploadSize){
		$this->_maxUploadSize = $uploadSize * 1024 * 1024;
	}
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
	 * 得到保存图片所在相对根目录的url路径
	 */
	protected function actionGetCurrentBaseImgUrl(){
		return $this->GetImgUrl($this->imageFloder,'common');
	}
	/**
	 * 得到保存图片所在相对根目录的文件夹路径
	 */
	protected function actionGetCurrentBaseImgDir(){
		return $this->GetImgDir($this->imageFloder,'common');
	}
	/**
	 * 通过图片的相对路径得到产品图片的url
	 */
	protected function actionGetUrlByRelativePath($str){
		return $this->GetImgUrl($this->imageFloder.$str,'common');
	}
	/**
	 * 通过图片的相对路径得到产品图片的绝对路径
	 */
	protected function actionGetDirByRelativePath(){
		return $this->GetImgDir($this->imageFloder.$str,'common');
	}
	
	
	/**
	 * @property $param_img_file | Array .
	 * upload image from web page , you can get image from $_FILE['XXX'] , 
	 * $param_img_file is get from $_FILE['XXX'].
	 * return , if success ,return image saved relative file path , like '/b/i/big.jpg'
	 * if fail, reutrn false;
	 */
	protected function actionSaveUploadImg($FILE){
		
		$size = $FILE['size']; 
		$file = $FILE['tmp_name'];
		$name = $FILE['name'];
		if($size > $this->getMaxUploadSize()){
			throw new InvalidValueException('upload image is to max than'. $this->getMaxUploadSize().' MB');
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
		$isMoved = @move_uploaded_file ( $file, $this->GetCurrentBaseImgDir().$imgSavedRelativePath);
		if($isMoved){
			$imgUrl = $this->getUrlByRelativePath($imgSavedRelativePath);
			$imgPath = $this->getDirByRelativePath($imgSavedRelativePath);
			return [$imgSavedRelativePath,$imgUrl,$imgPath];
		}else{
			return false;
		}
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
		
		$imgSaveFloder = CDir::createFloder($this->GetCurrentBaseImgDir(),[$first_str,$two_str]);
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