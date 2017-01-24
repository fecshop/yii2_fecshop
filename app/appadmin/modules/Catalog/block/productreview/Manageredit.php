<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Catalog\block\productreview;
use Yii;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
/**
 * block cms\article
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit  extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
	public  $_saveUrl;
	
	
	public function init(){
		$this->_saveUrl = CUrl::getUrl('catalog/productreview/managereditsave');
		parent::init();
	}
	# 传递给前端的数据 显示编辑form	
	public function getLastData(){
		
		return [
			'editBar' 	=> $this->getEditBar(),
			'review'	=> $this->_one,
			'textareas'	=> $this->_textareas,
			'lang_attr'	=> $this->_lang_attr,
			'saveUrl' 	=> $this->_saveUrl,
		];
	}
	
	public function setService(){
		$this->_service 	= Yii::$service->product->review;
	}
	
	
	
	public function getEditArr(){
		$activeStatus = Yii::$service->product->review->activeStatus();
		$refuseStatus = Yii::$service->product->review->refuseStatus();
		$noActiveStatus = Yii::$service->product->review->noActiveStatus();
		
		return [
			[
				'label'=>'Spu',
				'name'=>'product_spu',
				'display'=>[
					'type' => 'inputString',
				],
				'require' => 1,
			],
			
			[
				'label'=>'产品ID',
				'name'=>'product_id',
				'display'=>[
					'type' => 'inputString',
				],
				'require' => 1,
			],
			
			[
				'label'=>'评星',
				'name'=>'rate_star',
				'display'=>[
					'type' => 'select',
					'data' => [
						1 	=> '1星',
						2 	=> '2星',
						3 	=> '3星',
						4 	=> '4星',
						5 	=> '5星',
					]
				],
				'require' => 1,
				'default' => 4,
			],
			
			[
				'label'=>'评论人姓名',
				'name'=>'name',
				'display'=>[
					'type' => 'inputString',
				],
				'require' => 0,
			],
			
			[
				'label'=>'评论标题',
				'name'=>'summary',
				'display'=>[
					'type' => 'inputString',
				],
				'require' => 0,
			],
			
			[
				'label'=>'评论内容',
				'name'=>'review_content',
				'display'=>[
					'type' => 'textarea',
					'rows'	=> 14,
					'cols'	=> 110,
				],
				'require' => 0,
			],
			
			[
				'label'=>'审核状态',
				'name'=>'status',
				'display'=>[
					'type' => 'select',
					'data' => [
						$noActiveStatus => '未审核',
						$activeStatus	=> '审核通过',
						$refuseStatus 	=> '审核拒绝',
					]
				],
				'require' => 1,
			],
			
		];
	}
	/**
	 * save article data,  get rewrite url and save to article url key.
	 */
	public function save(){
		$request_param 		= CRequest::param();
		$this->_param		= $request_param[$this->_editFormData];
		/**
		 * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
		 * you must convert string datetime to time , use strtotime function.
		 */
		$identity = Yii::$app->user->identity;
		$audit_user_id = $identity['id'];
		$this->_param['audit_user'] = $audit_user_id;
		$this->_param['audit_date'] = time();
		
		//$this->_param['review_date'] = strtotime($this->_param['review_date']);
		$this->_service->save($this->_param);
		$errors = Yii::$service->helper->errors->get();
		if(!$errors ){
			echo  json_encode(array(
				"statusCode"=>"200",
				"message"=>"save success",
			));
			exit;
		}else{
			echo  json_encode(array(
				"statusCode"=>"300",
				"message"=>$errors,
			));
			exit;
		}
		
	}
	
	
	# 批量删除
	public function delete(){
		$ids = '';
		if($id = CRequest::param($this->_primaryKey)){
			$ids = $id;
		}else if($ids = CRequest::param($this->_primaryKey.'s')){
			$ids = explode(',',$ids);
		}
		$this->_service->remove($ids);
		$errors = Yii::$service->helper->errors->get();
		if(!$errors ){
			echo  json_encode(array(
				"statusCode"=>"200",
				"message"=>"remove data  success",
			));
			exit;
		}else{
			echo  json_encode(array(
				"statusCode"=>"300",
				"message"=>$errors,
			));
			exit;
		}
		
		
		
	}
	
	
	
	public function audit(){
		$ids = '';
		if($id = CRequest::param($this->_primaryKey)){
			$ids = $id;
		}else if($ids = CRequest::param($this->_primaryKey.'s')){
			$ids = explode(',',$ids);
		}
		$this->_service->auditReviewByIds($ids);
		
		$errors = Yii::$service->helper->errors->get();
		if(!$errors ){
			echo  json_encode(array(
				"statusCode"=>"200",
				"message"=>"批量审核评论通过 - 成功",
			));
			exit;
		}else{
			echo  json_encode(array(
				"statusCode"=>"300",
				"message"=>$errors,
			));
			exit;
		}
		
		
	}
	
	public function auditRejected(){
		$ids = '';
		if($id = CRequest::param($this->_primaryKey)){
			$ids = $id;
		}else if($ids = CRequest::param($this->_primaryKey.'s')){
			$ids = explode(',',$ids);
		}
		$this->_service->auditRejectedReviewByIds($ids);
		
		
		$errors = Yii::$service->helper->errors->get();
		if(!$errors ){
			echo  json_encode(array(
				"statusCode"=>"200",
				"message"=>"批量审核评论拒绝 - 成功",
			));
			exit;
		}else{
			echo  json_encode(array(
				"statusCode"=>"300",
				"message"=>$errors,
			));
			exit;
		}
		
		
	}
	
	
	
	
	
	
	
}