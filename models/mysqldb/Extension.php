<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mysqldb;

use yii\db\ActiveRecord;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Extension extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%extensions}}';
    }
    
    public function rules()
    {
        $rules = [
            
            ['namespace', 'required'],
            ['namespace', 'filter', 'filter' => 'trim'],
            ['namespace', 'string', 'length' => [1, 50]],
            ['namespace', 'validateNamespace'],
            
            
            ['package', 'required'],
            ['package', 'filter', 'filter' => 'trim'],
            ['package', 'string', 'length' => [1, 50]],
            
            ['folder', 'required'],
            ['folder', 'filter', 'filter' => 'trim'],
            ['folder', 'string', 'length' => [1, 50]],
            
            
            
            ['name', 'required'],
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'string', 'length' => [1, 50]],
            
            ['config_file_path', 'required'],
            ['config_file_path', 'filter', 'filter' => 'trim'],
            ['config_file_path', 'string', 'length' => [1, 255]],
            
            ['version', 'required'],
            ['version', 'filter', 'filter' => 'trim'],
            ['version', 'string', 'length' => [1, 50]],
            
        ];

        return $rules;
    }
    
    
    public function validateNamespace($attribute, $params)
    {
        if ($this->id) {
            $one = Extension::find()
                ->where(' id != :id AND namespace = :namespace ', [':id'=>$this->id, ':namespace'=>$this->namespace])
                ->one();
            if ($one['id']) {
                $this->addError($attribute, 'this namespace['.$this->namespace.'] is exist!');
            }
        } else {
            $one = Extension::find()
                ->where('namespace = :namespace', [':namespace' => $this->namespace])
                ->one();
            if ($one['id']) {
                $this->addError($attribute, 'this namespace['.$this->namespace.'] is exist!');
            }
        }
    }
}
