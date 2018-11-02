<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\admin;

//use fecshop\models\mysqldb\cms\StaticBlock;
use Yii;
use fecshop\services\Service;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class UrlKey extends Service
{
    public $numPerPage = 20;

    public $urlKeyTags;

    protected $_staticBlockModelName = '\fecshop\models\mysqldb\admin\UrlKey';

    protected $_staticBlockModel;

    /**
     *  language attribute.
     */
    protected $_lang_attr = [
    ];

    public function init()
    {
        parent::init();
        list($this->_staticBlockModelName, $this->_staticBlockModel) = Yii::mapGet($this->_staticBlockModelName);
    }

    public function getTags(){
        return $this->urlKeyTags;
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
	 * @return 按照tag，将资源（url_key）进行分组, 按照 tag_sort_order 进行排序
     * 根据传递的roleId 获取对应的url_key_id，将资源（url_key）默认选中
	 */
	public function getResourcesWithGroupAndSelected($role_id = 0){
        $selectRoleUrlKeys = [];
        if ($role_id) {
            $filter = [
                'numPerPage' 	=> 9000,
                'pageNum'		=> 1,
                'where'			=> [
                    ['role_id' => $role_id],
                ],
                'asArray' => true,
            ];
            $data = Yii::$service->admin->roleUrlKey->coll($filter);
            if (isset($data['coll']) && is_array($data['coll'])) {
                foreach ($data['coll'] as $one) {
                    $selectRoleUrlKeys[$one['url_key_id']] = $one['url_key_id'];
                }
            }
        }
		$filter = [ 
			'asArray' => true,
			'numPerPage' 	=> 4000,
      		'pageNum'		=> 1,
		];
		$coll = $this->coll($filter);
		$arr = [];
		if (!empty($coll['coll']) && is_array($coll['coll'])) {
			foreach ($coll['coll'] as $one) {
				 $tag = $one['tag'];
                 $id = $one['id'];
				 $one_arr = [
					'id' => $id,
					'name' => $one['name'],
					'tag_sort_order' => $one['tag_sort_order'],
					'url_key' => $one['url_key'],
				];
                if (isset($selectRoleUrlKeys[$id]) && $selectRoleUrlKeys[$id]) {
                    $one_arr['selected'] = true;
                } else {
                    $one_arr['selected'] = false;
                }
                $arr[$tag][] = $one_arr;
			}
		}
        // 按照 tag_sort_order 进行排序
        foreach ($arr as $k => $one) {
            $arr[$k] = \fec\helpers\CFunc::array_sort($one, 'tag_sort_order', 'asc', true);
        }
        //var_dump($arr);
        $urlKeyTags = $this->urlKeyTags;
        $arrSort = [];
        foreach ($urlKeyTags as $k => $v) {
            if (isset($arr[$k])) {
                $arrSort[$k] = $arr[$k];
            }

        }
		return $arrSort;
	}

    /**
     * @property $one|array
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    public function save($one)
    {
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        if (!($this->validateUrlKey($one))) {
            Yii::$service->helper->errors->add('url key 存在，您必须定义一个唯一的identify ');

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

    protected function validateUrlKey($one)
    {
        $url_key = $one['url_key'];
        $id = $this->getPrimaryKey();
        $primaryVal = isset($one[$id]) ? $one[$id] : '';
        $where = ['url_key' => $url_key];
        $query = $this->_staticBlockModel->find()->asArray();
        $query->where($where);
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
                // delete roleUrlKey
                Yii::$service->admin->roleUrlKey->removeByUrlKeyId($id);
            }
        } else {
            $id = $ids;
            $model = $this->_staticBlockModel->findOne($id);
            $model->delete();
            // delete roleUrlKey
            Yii::$service->admin->roleUrlKey->removeByUrlKeyId($id);
        }

        return true;
    }
}
