<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\cms\staticblock;

//use fecshop\models\mysqldb\cms\StaticBlock;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class StaticBlockMysqldb implements StaticBlockInterface
{
    public $numPerPage = 20;
    protected $_staticBlockModelName = '\fecshop\models\mysqldb\cms\StaticBlock';
    protected $_staticBlockModel;
    /**
     *  language attribute.
     */
    protected $_lang_attr = [
            'title',
            'content',
        ];
    
    public function __construct(){
        list($this->_staticBlockModelName,$this->_staticBlockModel) = Yii::mapGet($this->_staticBlockModelName);  
    }
    
    public function getPrimaryKey()
    {
        return 'id';
    }

    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            $one = $this->_staticBlockModel->findOne($primaryKey);
            foreach ($this->_lang_attr as $attrName) {
                if (isset($one[$attrName])) {
                    $one[$attrName] = unserialize($one[$attrName]);
                }
            }

            return $one;
        } else {
            return new $this->_staticBlockModelName();
        }
    }

    public function getByIdentify($identify)
    {
        $one = $this->_staticBlockModel->find()->asArray()->where([
            'identify' => $identify,
        ])->one();
        foreach ($this->_lang_attr as $attrName) {
            if (isset($one[$attrName])) {
                $one[$attrName] = unserialize($one[$attrName]);
            }
        }

        return $one;
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
        $query = $this->_staticBlockModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        $coll = $query->all();
        if (!empty($coll)) {
            foreach ($coll as $k => $one) {
                foreach ($this->_lang_attr as $attr) {
                    $one[$attr] = $one[$attr] ? unserialize($one[$attr]) : '';
                }
                $coll[$k] = $one;
            }
        }
        //var_dump($one);
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }

    /**
     * @property $one|array
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    public function save($one)
    {
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        if (!($this->validateIdentify($one))) {
            Yii::$service->helper->errors->add('StaticBlock: identify存在，您必须定义一个唯一的identify ');

            return;
        }
        if ($primaryVal) {
            $model = $this->_staticBlockModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('static block '.$this->getPrimaryKey().' is not exist');

                return;
            }
        } else {
            $model = new $this->_staticBlockModelName();
            $model->created_at = time();
            $model->created_user_id = \fec\helpers\CUser::getCurrentUserId();
        }
        $model->updated_at = time();
        foreach ($this->_lang_attr as $attrName) {
            if (is_array($one[$attrName]) && !empty($one[$attrName])) {
                $one[$attrName] = serialize($one[$attrName]);
            }
        }
        $primaryKey = $this->getPrimaryKey();
        $model      = Yii::$service->helper->ar->save($model, $one);
        $primaryVal = $model[$primaryKey];

        return true;
    }

    protected function validateIdentify($one)
    {
        $identify = $one['identify'];
        $id = $this->getPrimaryKey();
        $primaryVal = isset($one[$id]) ? $one[$id] : '';
        $where = ['identify' => $identify];
        $query = $this->_staticBlockModel->find()->asArray();
        $query->where(['identify' => $identify]);
        if ($primaryVal) {
            $query->andWhere(['<>', $id, $primaryVal]);
        }
        $one = $query->one();
        if (!empty($one)) {
            return false;
        }

        return true;
    }

    public function remove($ids)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $model = $this->_staticBlockModel->findOne($id);
                $model->delete();
            }
        } else {
            $id = $ids;
            foreach ($ids as $id) {
                $model = $this->_staticBlockModel->findOne($id);
                $model->delete();
            }
        }

        return true;
    }
}
