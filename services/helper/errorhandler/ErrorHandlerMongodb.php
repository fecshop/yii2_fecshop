<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\helper\errorhandler;

use fecshop\services\Service;
use Yii;

/**
 * Helper Errors services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ErrorHandlerMongodb extends Service implements ErrorHandlerInterface
{
    protected $_errorHandlerModelName = '\fecshop\models\mongodb\ErrorHandlerLog';

    protected $_errorHandlerModel;
    
    public function init()
    {
        parent::init();
        list($this->_errorHandlerModelName, $this->_errorHandlerModel) = \Yii::mapGet($this->_errorHandlerModelName);
    }
    
    public function getPrimaryKey()
    {
        return '_id';
    }

    /**
     * @param $code | Int, http 错误码
     * @param $message | String, 错误的具体信息
     * @param $file | string, 发生错误的文件
     * @param $line | Int, 发生错误所在文件的代码行
     * @param $created_at | Int, 发生错误的执行时间戳
     * @param $ip | string, 访问人的ip
     * @param $name | string, 错误的名字
     * @param $trace_string | string, 错误的追踪信息
     * @return 返回错误存储到mongodb的id，作为前端显示的错误编码
     * 该函数从errorHandler得到错误信息，然后保存到mongodb中。
     */
    public function saveByErrorHandler(
        $code,
        $message,
        $file,
        $line,
        $created_at,
        $ip,
        $name,
        $trace_string,
        $url,
        $req_info=[]
    ) {
        $category = Yii::$service->helper->getAppName();
        $model = new $this->_errorHandlerModelName();
        $model->category     = $category;
        $model->code         = $code;
        $model->message      = $message;
        $model->file         = $file;
        $model->line         = $line;
        $model->created_at   = $created_at;
        $model->ip           = $ip;
        $model->name         = $name;
        $model->url          = $url;
        $model->request_info = $req_info;
        $model->trace_string = $trace_string;
        $model->save();
        return (string)$model[$this->getPrimaryKey()];
    }
    
    /**
     * 通过主键，得到errorHandler对象。
     */
    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            return $this->_errorHandlerModel->findOne($primaryKey);
        }
    }
    
    /*
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
            'where'			=> [
                ['>','price',1],
                ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     */
    public function coll($filter = '')
    {
        $query = $this->_errorHandlerModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        $coll = $query->all();
        if (!empty($coll)) {
            foreach ($coll as $k => $one) {
                $coll[$k] = $one;
            }
        }
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }
}
