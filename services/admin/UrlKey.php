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
    const URLKEY_LABEL_ARR = 'appadmin_urlkey_label_cache_arr'; 
    public $numPerPage = 20;

    public $urlKeyTags;
    protected $_urlKeyTags;
    
    // 没有在数据库中添加的url key
    public $addUrlKeyAndLabelArr = [
        '/fecadmin/login/index' => 'Login',
        '/fecadmin/logout/index' => 'Logout',
    ];
    
    protected $_modelName = '\fecshop\models\mysqldb\admin\UrlKey';

    protected $_mode;

    /**
     *  language attribute.
     */
    protected $_lang_attr = [
    ];

    public function init()
    {
        parent::init();
        list($this->_modelName, $this->_mode) = Yii::mapGet($this->_modelName);
    }

    public function getTags($translate = true){
        $key = $translate ? 1 : 2;
        if (!$this->_urlKeyTags[$key]) {
            if (is_array($this->urlKeyTags)) {
                foreach ($this->urlKeyTags as $k => $v) {
                    if ($translate) {
                        $this->_urlKeyTags[$key][$k] = Yii::$service->page->translate->__($v);
                    } else {
                        $this->_urlKeyTags[$key][$k] = $v;
                    }
                    
                }
            }
        }
        return $this->_urlKeyTags[$key];
    }

    public function getPrimaryKey()
    {
        return 'id';
    }

    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            $one = $this->_mode->findOne($primaryKey);
            foreach ($this->_lang_attr as $attrName) {
                if (isset($one[$attrName])) {
                    $one[$attrName] = unserialize($one[$attrName]);
                }
            }

            return $one;
        } else {
            return new $this->_modelName();
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
        $query = $this->_mode->find();
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
     * @return array 得到urlKey 和 label 对应的数组。
     *  这样可以通过url_key得到当前操作的菜单的name
     */
    public function getUrlKeyAndLabelArr(){
        $arr = Yii::$app->cache->get(self::URLKEY_LABEL_ARR);
        if (!$arr) {
            $arr = [];
            $filter = [
                'fetchAll' => true,
                'asArray' => true,
            ];
            $data = $this->coll($filter);
            if (is_array($data['coll'])) {
                $tags = $this->getTags(false);
                foreach ($data['coll'] as $one) {
                    $url_key =  $one['url_key'];
                    $label = $tags[$one['tag']] .' '. $one['name'];
                    $arr[$url_key] = $label;
                }
            }
            $addArr = $this->getAddUrlKeyAndLabelArr();
            if (is_array($addArr)) {
                $arr = array_merge($arr, $addArr);
            }
            Yii::$app->cache->set(self::URLKEY_LABEL_ARR, $arr);
        }
        
        return $arr;
    }
    
    protected function getAddUrlKeyAndLabelArr(){
        
        return $this->addUrlKeyAndLabelArr;
    }
    
    
    
	/**
	 * @return 按照tag，将资源（url_key）进行分组, 按照 tag_sort_order 进行排序
     * 根据传递的roleId 获取对应的url_key_id，将资源（url_key）默认选中
	 */
	public function getResourcesWithGroupAndSelected($role_id = 0){
        $selectRoleUrlKeys = [];
        if ($role_id) {
            $filter = [
                'where'			=> [
                    ['role_id' => $role_id],
                ],
                'asArray' => true,
                'fetchAll' => true,
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
			'fetchAll' => true,
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
        $urlKeyTags = $this->getTags();
        $arrSort = [];
        foreach ($urlKeyTags as $k => $v) {
            if (isset($arr[$k])) {
                $arrSort[$k] = $arr[$k];
            }

        }
		return $arrSort;
	}

    public $can_not_delete = 1;
    /**
     * @param $one|array
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    public function save($one)
    {
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        if (!($this->validateUrlKey($one))) {
            Yii::$service->helper->errors->add('The url key exists, you must define a unique url key');

            return;
        }
        if ($primaryVal) {
            $model = $this->_mode->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('Url key {primaryKey} is not exist', ['primaryKey' => $this->getPrimaryKey()]);

                return false;
            }
            if ($model->can_delete == $this->can_not_delete) {
                Yii::$service->helper->errors->add('resource(url key) created by system, can not edit and save');

                return false;
            }
        } else {
            $model = new $this->_modelName();
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
        $query = $this->_mode->find()->asArray();
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
                $model = $this->_mode->findOne($id);
                if ($model->can_delete == $this->can_not_delete) {
                    Yii::$service->helper->errors->add('resource(url key) created by system, can not remove');

                    return false;
                }

                $model->delete();
                // delete roleUrlKey
                Yii::$service->admin->roleUrlKey->removeByUrlKeyId($id);
            }
        } else {
            $id = $ids;
            $model = $this->_mode->findOne($id);
            if ($model->can_delete == $this->can_not_delete) {
                Yii::$service->helper->errors->add('resource(url key) created by system, can not remove');

                return false;
            }
            $model->delete();
            // delete roleUrlKey
            Yii::$service->admin->roleUrlKey->removeByUrlKeyId($id);
        }

        return true;
    }
}
