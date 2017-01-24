<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\models\mongodb;
use Yii;
use yii\mongodb\ActiveRecord;
use yii\helpers\CDate;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
 
class Newsletter extends ActiveRecord
{
    
    
	public static function collectionName()
    {
	   return 'newsletter';
    }
	
	
	public function attributes()
    {
       return [
		'_id', 
		'email', 
		'created_at',
		'send_mail_count'
		];
    }
	
	public function rules()
    {
		$parent_rules  = parent::rules();
		$current_rules = [
			['email', 'filter', 'filter' => 'trim'],
			['email', 'email'],
		//	['email', 'validateEmail'],
		];
		return array_merge($parent_rules,$current_rules) ;
    }
	/*
	public function validateEmail($attribute, $params){
		//$user = User::findByUsername($this->username)
		if($this->_id){
			$one = Newsletter::find()->where('<>','_id',$this->_id)
				->andWhere('email' => $this->email)
				->one();
			if($one['id']){
				$this->addError($attribute,"the email is exist,you can not change!");
			}
		}else{
			$one = Newsletter::find()->where('email' => $this->email)
				->one();
			if($one['id']){
				$this->addError($attribute,"the email is subscription by other");
			}
		}
		
	}
	*/
	
	public function beforeSave($insert)  
    {  
        if (parent::beforeSave($insert)) {  
           $now_date = CDate::getCurrentDateTime();
            if($insert == self::EVENT_BEFORE_INSERT)
				$this->created_at = $now_date;
            $this->updated_at = $now_date;
            return true;  
        } else {  
            return false;  
        }  
    } 
	
}