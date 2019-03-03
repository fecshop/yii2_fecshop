<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\models\mysqldb\admin;
use Yii;
use fec\helpers\CDate;
use yii\db\ActiveRecord;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Config extends ActiveRecord
{
    /**
     * @inheritdoc
     */
	# 设置table
    public static function tableName()
    {
        return '{{%admin_config}}';
    }
	
	 /**
     * @inheritdoc
     */
	# 设置 status  默认  ，以及取值的区间
    public function rules()
    {
        return [
            ['label', 'filter', 'filter' => 'trim'],
			['key', 'filter', 'filter' => 'trim'],
			['value', 'filter', 'filter' => 'trim'],
			['description', 'filter', 'filter' => 'trim'],
			['key', 'valiadateKey'],
			
		];
    }
	
	public function valiadateKey($attribute, $params){
		$key = $this->key;
		$id = $this->id;
		if($id){
			$one = self::find()->where([
				"key" =>$key,
				])
			->andWhere(
				" id != :id ",[':id' => $id]
			)
			->one()
			;
			if($one['id']){
				$this->addError($attribute,"该条目已经存在[$key]");
			}
		}else{
			$one =  self::find()->where([
				"key" =>$key,
				])
			->one()
			;
			if($one['id']){
				$this->addError($attribute,"该条目已经存在[$key]");
			}
		}
	}
	
	public function beforeSave($insert)  
    {  
        if (parent::beforeSave($insert)) {  
            if ($insert == self::EVENT_BEFORE_INSERT) {  
                $user = Yii::$app->user->identity;
				$account = $user['username'];
				$this->created_person = $account;
				$this->created_at = CDate::getCurrentDateTime();
			} 
            $this->updated_at = CDate::getCurrentDateTime(); 
            
            return true;  
        } else {  
        
            return false;
        }  
    } 
}
