<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Catalog\block\productinfo;
use Yii;
use yii\base\InvalidValueException;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
/**
 * block cms\article
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Imageupload
{
	
	public function upload(){
		
		if(empty($_FILES)) {
			throw new InvalidValueException('$_FILES is empty.');
		}
		$index = 0;		//$_FILES 以文件name为数组下标，不适用foreach($_FILES as $index=>$file)
		$post = Yii::$app->request->post();
		if($post['thisindex']){
			$rel_index = $post['thisindex'];
		}else{
			$rel_index = $index ;
		}
		$img_str = "";
		foreach($_FILES as $FILE){
			$saveImgInfo = Yii::$service->product->image->saveProductUploadImg($FILE);
			$rel_index++;
			if(is_array($saveImgInfo) && !empty($saveImgInfo)  ){
				list($imgSavedRelativePath,$imgUrl) = $saveImgInfo;
				$img_str .='<tr class="p_img" rel="'.$rel_index.'" style="border-bottom:1px solid #ccc;">
								<td style="width:120px;text-align:center;"><img  rel="'.$imgSavedRelativePath.'" style="width:100px;height:100px;" src="'.$imgUrl.'"></td>
								<td style="width:220px;text-align:center;"><input style="height:10px;width:200px;" type="text" class="image_label" name="image_label"  /></td>
								<td style="width:220px;text-align:center;"><input style="height:10px;width:200px;" type="text" class="sort_order"  name="sort_order"  /></td>
								<td style="width:30px;text-align:center;"><input type="radio" name="image"  value="'.$imgSavedRelativePath.'" /></td>
								<td style="padding:0 0 0 20px;"><a class="delete_img btnDel" href="javascript:void(0)">删除</a></td>
							</tr>';
			}
			$index++;
		}
		echo json_encode(array(
			"return_status" => "success",
			"img_str"=>$img_str,
		));
	}
}



