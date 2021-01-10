<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\category;

use fecshop\models\mongodb\Category;
use fecshop\services\Service;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CategoryMysqldb extends Service implements CategoryInterface
{
    public $numPerPage = 20;
    
    protected $_categoryModelName = '\fecshop\models\mysqldb\Category';

    protected $_categoryModel;
    
    protected $_categoryProductModelName = '\fecshop\models\mysqldb\CategoryProduct';

    protected $_categoryProductModel;
    
    protected $serializeAttrs = [
        'name',
        'menu_custom',
        'description',
        'title',
        'meta_keywords',
        'meta_description',
    ];
    
    public function init()
    {
        parent::init();
        list($this->_categoryModelName, $this->_categoryModel) = Yii::mapGet($this->_categoryModelName);
        list($this->_categoryProductModelName, $this->_categoryProductModel) = \Yii::mapGet($this->_categoryProductModelName);
    }
    
    // 保存的数据进行serialize序列化
    protected function serializeSaveData($one) 
    {
        if (!is_array($one) && !is_object($one)) {
            
            return $one;
        }
        foreach ($one as $k => $v) {
            if (in_array($k, $this->serializeAttrs)) {
                $one[$k] = serialize($v);
            }
        }
        
        return $one;
    }
    // 保存的数据进行serialize序列化
    protected function unserializeData($one) 
    {
        if (!is_array($one) && !is_object($one)) {
            
            return $one;
        }
        foreach ($one as $k => $v) {
            if (in_array($k, $this->serializeAttrs)) {
                $one[$k] = unserialize($v);
            }
        }
        
        return $one;
    }
    
    /**
     * 通过主键，得到Category对象。
     */
    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            $one = $this->_categoryModel->findOne($primaryKey);
            
            return $this->unserializeData($one) ;
        } else {
            
            return new $this->_categoryModelName;
        }
    }
    
    /**
     * 通过主键，得到Category对象。
     */
    public function findOne($where)
    {
        $one = $this->_categoryModel->findOne($where);
        
        return $this->unserializeData($one) ;
    }
    
    /**
     * 通过url_key，得到Category对象。
     */
    public function getByUrlKey($urlKey)
    {
        if ($urlKey) {
            $urlKey = "/".trim($urlKey, "/");
            $one = $this->_categoryModel->findOne(['url_key' => $urlKey]);
            
            return $this->unserializeData($one) ;
        } else {
            
            return new $this->_categoryModelName;
        }
    }
    
    /**
     * 返回主键。
     */
    public function getPrimaryKey()
    {
        return 'id';
    }

    /**
     * 得到分类激活状态的值
     */
    public function getCategoryEnableStatus()
    {
        $model = $this->_categoryModel;
        
        return $model::STATUS_ENABLE;
    }

    /**
     * 得到分类在menu中显示的状态值
     */
    public function getCategoryMenuShowStatus()
    {
        $model = $this->_categoryModel;
        
        return $model::MENU_SHOW;
    }

    /*
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     * 		'where'			=> [
     *			['>','price','1'],
     *			['<','price','10'],
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     */
    public function coll($filter = '')
    {
        $query = $this->_categoryModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        $coll = $query->all();
        $arr = [];
        foreach ($coll as $one) {
            $arr[] = $this->unserializeData($one) ;
        }
        
        return [
            'coll' => $arr,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }
    /**
     * 和coll函数类型，但是，对于查询出来的数据不做任何处理
     */
    public function apiColl($filter = '')
    {
        $query = $this->_categoryModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        $coll = $query->all();
        
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }

    /**
     *  得到总数.
     */
    public function collCount($filter = '')
    {
        $query = $this->_categoryModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);

        return $query->count();
    }

    /**
     * @param $one|array , save one data . 分类数组
     * @param $originUrlKey|string , 分类的在修改之前的url key.（在数据库中保存的url_key字段，如果没有则为空）
     * 保存分类，同时生成分类的伪静态url（自定义url），如果按照name生成的url或者自定义的urlkey存在，系统则会增加几个随机数字字符串，来增加唯一性。
     */
    public function save($one, $originUrlKey = 'catalog/category/index')
    {
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        if ($primaryVal) {
            $model = $this->_categoryModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('Category {primaryKey} is not exist', ['primaryKey' => $this->getPrimaryKey()]);

                return false;
            }
            $parent_id = $model['parent_id'];
        } else {
            $model = new $this->_categoryModelName;
            $model->created_at = time();
            $model->created_user_id = \fec\helpers\CUser::getCurrentUserId();
            $parent_id = $one['parent_id'];
        }
        // 增加分类的级别字段level，从1级级别开始依次类推。
        if ($parent_id === 0) {
            $model['level'] = 1;
        } else {
            $parent_model = $this->_categoryModel->findOne($parent_id);
            if ($parent_level = $parent_model['level']) {
                $model['level'] = $parent_level + 1;
            }
        }
        $model->updated_at = time();
        unset($one['id']);
        $one['status']    = (int)$one['status'];
        $one['menu_show'] = (int)$one['menu_show'];
        $allowMenuShowArr = [ $model::MENU_SHOW, $model::MENU_NOT_SHOW];
        if (!in_array($one['menu_show'], $allowMenuShowArr)) {
            $one['menu_show'] = $model::MENU_SHOW;
        }
        $allowStatusArr = [ $model::STATUS_ENABLE, $model::STATUS_DISABLE];
        if (!in_array($one['status'], $allowStatusArr)) {
            $one['status'] = $model::STATUS_ENABLE;
        }
        $defaultLangName = Yii::$service->fecshoplang->getDefaultLangAttrVal($one['name'], 'name');
        $one = $this->serializeSaveData($one);
        $saveStatus = Yii::$service->helper->ar->save($model, $one);
        $primaryVal = $model->id;
        $originUrl = $originUrlKey.'?'.$this->getPrimaryKey() .'='. $primaryVal;
        $originUrlKey = isset($one['url_key']) ? $one['url_key'] : '';
        $defaultLangTitle = $defaultLangName;
        $urlKey = Yii::$service->url->saveRewriteUrlKeyByStr($defaultLangTitle, $originUrl, $originUrlKey);
        $model->url_key = $urlKey;
        $model->save();
    
        return $model;
    }
    /** 
     * @param $arr | array
     * 用于同步mongodb数据库到mysql数据库中
     */
    public function sync($arr)
    {
        $originUrlKey = 'catalog/category/index';
        $origin_mongo_parent_id = $arr['parent_id'];
        $origin_mongo_id = $arr['_id'];
        unset($arr['parent_id']);
        unset($arr['_id']);
        $model = $this->_categoryModel->findOne([
            'origin_mongo_id' => $origin_mongo_id
        ]);
        if (!$model['origin_mongo_id']) {
            $model = new $this->_categoryModelName;
            $model->created_at = time();
        }
        $model->origin_mongo_id = $origin_mongo_id;
        $model->origin_mongo_parent_id = $origin_mongo_parent_id;
        $arr = $this->serializeSaveData($arr);
        $saveStatus = Yii::$service->helper->ar->save($model, $arr);
        $originUrl = $originUrlKey.'?'.$this->getPrimaryKey() .'='. $model->id;
        $originUrlKey = isset($model['url_key']) ? $model['url_key'] : '';
        $defaultLangTitle = Yii::$service->fecshoplang->getDefaultLangAttrVal($arr['name'], 'name');
        $urlKey = Yii::$service->url->saveRewriteUrlKeyByStr($defaultLangTitle, $originUrl, $originUrlKey);
        $model->url_key = $urlKey;
        return $model->save();
    }

    /**
     * @param $id | String  主键值
     * 通过主键值找到分类，并且删除分类在url rewrite表中的记录
     * 查看这个分类是否存在子分类，如果存在子分类，则删除所有的子分类，以及子分类在url rewrite表中对应的数据。
     */
    public function remove($ids)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove ids is empty');

            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            $deleteAll = true;
            foreach ($ids as $id) {
                $model = $this->_categoryModel->findOne($id);
                if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                    $url_key = $model['url_key'];
                    Yii::$service->url->removeRewriteUrlKey($url_key);
                    $model->delete();
                    $this->removeChildCate($id);
                    // delete category product relation
                    $this->removeCategoryProductRelationByCategoryId($id);
                } else {
                    Yii::$service->helper->errors->add("Category Remove Errors:ID:{id} is not exist.", ['id' => $id]);
                    $deleteAll = false;
                }
            }
            
            return $deleteAll;
        } else {
            $id = $ids;
            //echo $id;exit;
            $model = $this->_categoryModel->findOne($id);
            if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                $url_key = $model['url_key'];
                Yii::$service->url->removeRewriteUrlKey($url_key);
                $model->delete();
                $this->removeChildCate($id);
            } else {
                Yii::$service->helper->errors->add("Category Remove Errors:ID:{id} is not exist." , ['id' => $id]);

                return false;
            }
            $this->removeCategoryProductRelationByCategoryId($id);
        }
        
        return true;
    }

    protected function removeChildCate($id)
    {
        $data = $this->_categoryModel->find()->where(['parent_id'=>$id])->all();
        if (!empty($data)) {
            foreach ($data as $one) {
                $idVal = (string) $one['id'];
                if ($this->hasChildCategory($idVal)) {
                    $this->removeChildCate($idVal);
                }
                $url_key = $one['url_key'];
                Yii::$service->url->removeRewriteUrlKey($url_key);
                $one->delete();
                // delete category product relation
                $this->removeCategoryProductRelationByCategoryId($idVal);
            }
        }
    }

    /**
     *  得到分类的树数组。
     *  数组中只有  id  name(default language), child(子分类) 等数据。
     *  目前此函数仅仅用于后台对分类的编辑使用。 appadmin.
     */
    public function getTreeArr($rootCategoryId = '', $lang = '', $appserver=false, $level = 1)
    {
        $arr = [];
        if (!$lang) {
            $lang = Yii::$service->fecshoplang->defaultLangCode;
        }
        if (!$rootCategoryId) {
            $where = ['parent_id' => 0];
        } else {
            $where = ['parent_id' => $rootCategoryId];
        }
        if ($appserver) {
            $where['status']= $this->getCategoryEnableStatus();
            $where['menu_show']= $this->getCategoryMenuShowStatus();
        }
        $orderBy = ['sort_order' => SORT_DESC];
        $categorys = $this->_categoryModel->find()
                    ->asArray()
                    ->where($where)
                    ->orderBy($orderBy)
                    ->all();
        $idKey = $this->getPrimaryKey();
        if (!empty($categorys)) {
            foreach ($categorys as $cate) {
                $cate = $this->unserializeData($cate) ;
                $idVal = $cate[$idKey];
                $arr[$idVal] = [
                    $idKey    => $idVal,
                    'level'   => $level,
                    'name'    => Yii::$service->fecshoplang->getLangAttrVal($cate['name'], 'name', $lang),
                    'thumbnail_image' => $cate['thumbnail_image'],
                ];
                if ($appserver) {
                    $arr[$idVal]['url'] = '/catalog/category/'.$idVal;
                }
                if ($this->hasChildCategory($idVal)) {
                    $arr[$idVal]['child'] = $this->getTreeArr($idVal, $lang, $appserver, $level+1);
                }
            }
        }

        return $arr;
    }

    public function hasChildCategory($idVal)
    {
        $one = $this->_categoryModel->find()->asArray()->where(['parent_id'=>$idVal])->one();
        if (!empty($one)) {
            
            return true;
        }

        return false;
    }

    /**
     * @param $parent_id|string
     * 通过当前分类的parent_id字段（当前分类的上级分类id），得到所有的上级信息数组。
     * 里面包含的信息为：name，url_key。
     * 譬如一个分类为三级分类，将他的parent_id传递给这个函数，那么，他返回的数组信息为[一级分类的信息（name，url_key），二级分类的信息（name，url_key）].
     * 目前这个功能用于前端分类页面的面包屑导航。
     */
    public function getAllParentInfo($parent_id)
    {
        if ($parent_id) {
            $parentModel = $this->_categoryModel->findOne($parent_id);
            $parentModel =$this->unserializeData($parentModel) ;
            $parent_parent_id = $parentModel['parent_id'];
            $parent_category = [];
            if ($parent_parent_id !== 0) {
                $parent_category[] = [
                    'name'   => $parentModel['name'],
                    'url_key'=>$parentModel['url_key'],
                ];
                $parent_category = array_merge($this->getAllParentInfo($parent_parent_id), $parent_category);
            } else {
                $parent_category[] = [
                    'name'   => $parentModel['name'],
                    'url_key'=>$parentModel['url_key'],
                ];
            }

            return $parent_category;
        }
    }

    protected function getParentCategory($parent_id)
    {
        if ($parent_id === 0) {
            
            return [];
        }
        $category = $this->_categoryModel->find()->asArray()->where(['id' => $parent_id])->one();
        $category =$this->unserializeData($category) ;
        if (isset($category['id']) && !empty($category['id'])) {
            $currentUrlKey = $category['url_key'];
            $currentName = $category['name'];
            $currentId = $category['id'];

            $currentCategory[] = [
                '_id'        => $currentId,
                'name'       => $currentName,
                'url_key'    => $currentUrlKey,
                'parent_id'  => $category['parent_id'],
            ];
            $parentCategory = $this->getParentCategory($category['parent_id']);

            return array_merge($parentCategory, $currentCategory);
        } else {
            
            return [];
        }
    }

    /**
     * @param $category_id|string  当前的分类_id
     * @param $parent_id|string  当前的分类上级id parent_id
     * 这个功能是点击分类后，在产品分类页面侧栏的子分类菜单导航，详细的逻辑如下：
     * 1.如果level为一级，那么title部分为当前的分类，子分类为一级分类下的二级分类
     * 2.如果level为二级，那么将所有的二级分类列出，当前的二级分类，会列出来当前二级分类对应的子分类
     * 3.如果level为三级，那么将所有的二级分类列出。当前三级分类的所有姊妹分类（同一个父类）列出，当前三级分类如果有子分类，则列出
     * 4.依次递归。
     * 具体的显示效果，请查看appfront 对应的分类页面。
     */
    public function getFilterCategory($category_id, $parent_id)
    {
        $returnData = [];
        $primaryKey = $this->getPrimaryKey();
        $currentCategory = $this->_categoryModel->findOne($category_id);
        $currentCategory =$this->unserializeData($currentCategory) ;
        $currentUrlKey = $currentCategory['url_key'];
        $currentName = $currentCategory['name'];
        $currentId = $currentCategory['id'];
        $returnData['current'] = [
            '_id'        => $currentId,
            'name'       => $currentName,
            'url_key'    => $currentUrlKey,
            'parent_id'  => $currentCategory['parent_id'],
        ];
        if ($currentCategory['parent_id']) {
            $allParent = $this->getParentCategory($currentCategory['parent_id']);
            $allParent[] = $returnData['current'];
            $data = $this->getAllParentCate($allParent);
        } else {
            $data = $this->getOneLevelCateChild($returnData['current']);
        }

        return $data;
    }

    protected function getOneLevelCateChild($category)
    {
        $id = $category['_id'];
        $name = $category['name'];
        $url_key = $category['url_key'];
        $cate = $this->_categoryModel->find()->asArray()->where([
            'parent_id' => $id,
            'status' => $this->getCategoryEnableStatus(),
            'menu_show'  => $this->getCategoryMenuShowStatus(),
        ])->all();
        if (is_array($cate) && !empty($cate)) {
            foreach ($cate as $one) {
                $one =$this->unserializeData($one) ;
                $c_id = $one['id'];
                $data[$c_id] = [
                    'name'        => $one['name'],
                    'url_key'    => $one['url_key'],
                    'parent_id'    => $one['parent_id'],
                ];
            }
        }

        return $data;
    }

    protected function getAllParentCate($allParent)
    {
        $d = $allParent;
        $data = [];
        if (is_array($allParent) && !empty($allParent)) {
            foreach ($allParent as $k => $category) {
                unset($d[$k]);
                $category_id = $category['_id'];
                $parent_id = $category['parent_id'];
                if ($parent_id) {
                    $cate = $this->_categoryModel->find()->asArray()->where([
                        'parent_id' => $parent_id,
                        'status' => $this->getCategoryEnableStatus(),
                        'menu_show'  => $this->getCategoryMenuShowStatus(),
                    ])->all();
                    if (is_array($cate) && !empty($cate)) {
                        foreach ($cate as $one) {
                            $one =$this->unserializeData($one) ;
                            $c_id = $one['id'];
                            $data[$c_id] = [
                                'name'        => $one['name'],
                                'url_key'    => $one['url_key'],
                                'parent_id'    => $one['parent_id'],
                            ];
                            if (($c_id == $category_id) && !empty($d)) {
                                $data[$c_id]['child'] = $this->getAllParentCate($d);
                            }
                            if (($c_id == $category_id) && empty($d)) {
                                $child_cate = $this->getChildCate($c_id);
                                $data[$c_id]['current'] = true;
                                if (!empty($child_cate)) {
                                    $data[$c_id]['child'] = $child_cate;
                                }
                            }
                        }
                    }
                    
                    break;
                }
            }
        }

        return $data;
    }
    
    public function getChildCategory($category_id) {
        
        return $this->getChildCate($category_id);
    }

    protected function getChildCate($category_id)
    {
        $data = $this->_categoryModel->find()->asArray()->where([
            'parent_id' => $category_id,
            'status' => $this->getCategoryEnableStatus(),
            'menu_show'  => $this->getCategoryMenuShowStatus(),
        ])->orderBy(['sort_order' => SORT_DESC])->all();
        $arr = [];
        if (is_array($data) && !empty($data)) {
            foreach ($data as $one) {
                $one =$this->unserializeData($one) ;
                $currentUrlKey = $one['url_key'];
                $currentName = $one['name'];
                $currentId = (string) $one['id'];
                
                $arr[$currentId] = [
                    'category_id' 		=> $currentId,
                    'name'        => $currentName,
                    'url_key'    => $currentUrlKey,
                    'parent_id'    => $one['parent_id'],
                    'thumbnail_image' => $one['thumbnail_image'],
                    'image' => $one['image'],
                ];
            }
        }
        
        return $arr;
    }
    
    public function removeCategoryProductRelationByCategoryId($category_id)
    {
        return $this->_categoryProductModel->deleteAll(['category_id' => $category_id]);
    }
    
    
    /**
     * @param $one|array , save one data . 分类数组
     * @param $originUrlKey|string , 分类的在修改之前的url key.（在数据库中保存的url_key字段，如果没有则为空）
     * 保存分类，同时生成分类的伪静态url（自定义url），如果按照name生成的url或者自定义的urlkey存在，系统则会增加几个随机数字字符串，来增加唯一性。
     * 和save方法不同的是，如果category_id，查询不到，那么插入数据，将新插入数据的id = excel category id
     */
    public function excelSave($one, $originUrlKey = 'catalog/category/index')
    {
        $parent_id = $one['parent_id'];
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        if (!$primaryVal) {
            Yii::$service->helper->errors->add('category id can not empty');
            
            return false;
        }
        $model = $this->_categoryModel->findOne($primaryVal);
        if (!isset($model[$this->getPrimaryKey()]) || !$model[$this->getPrimaryKey()]) {
            $model = new $this->_categoryModelName;
            $model[$this->getPrimaryKey()] = $one[$this->getPrimaryKey()];
            $model->created_at = time();
            $model->created_user_id = \fec\helpers\CUser::getCurrentUserId();
        } else {
            // 多语言属性，如果您有其他的多语言属性，可以自行二开添加。
            $model = $this->unserializeData($model) ;
            $name =$model['name'];
            $title = $model['title'];
            $meta_keywords = $model['meta_keywords'];
            $meta_description = $model['meta_description'];
            $description = $model['description'];
            //var_dump($title);var_dump($one['title']);
            if (is_array($one['name']) && !empty($one['name'])) {
                $one['name'] = array_merge((is_array($name) ? $name : []), $one['name']);
            }
            if (is_array($one['title']) && !empty($one['title'])) {
                $one['title'] = array_merge((is_array($title) ? $title : []), $one['title']);
            }
            if (is_array($one['meta_keywords']) && !empty($one['meta_keywords'])) {
                $one['meta_keywords'] = array_merge((is_array($meta_keywords) ? $meta_keywords : []), $one['meta_keywords']);
            }
            if (is_array($one['meta_description']) && !empty($one['meta_description'])) {
                $one['meta_description'] = array_merge((is_array($meta_description) ? $meta_description : []), $one['meta_description']);
            }
            if (is_array($one['description']) && !empty($one['description'])) {
                $one['description'] = array_merge((is_array($description) ? $description : []), $one['description']);
            }
        }
        // 增加分类的级别字段level，从1级级别开始依次类推。
        if ($parent_id == 0) {
            $model['level'] = 1;
        } else {
            $parent_model = $this->_categoryModel->findOne($parent_id);
            if ($parent_level = $parent_model['level']) {
                $model['level'] = $parent_level + 1;
            }
        }
        $model->updated_at = time();
        unset($one[$this->getPrimaryKey()]);
        $one['status']    = (int)$one['status'];
        $one['menu_show'] = (int)$one['menu_show'];
        $allowMenuShowArr = [ $model::MENU_SHOW, $model::MENU_NOT_SHOW];
        if (!in_array($one['menu_show'], $allowMenuShowArr)) {
            $one['menu_show'] = $model::MENU_SHOW;
        }
        $allowStatusArr = [ $model::STATUS_ENABLE, $model::STATUS_DISABLE];
        if (!in_array($one['status'], $allowStatusArr)) {
            $one['status'] = $model::STATUS_ENABLE;
        }
        $one = $this->serializeSaveData($one);
        $saveStatus = Yii::$service->helper->ar->save($model, $one);
        $primaryVal = $model->id;
        $originUrl = $originUrlKey.'?'.$this->getPrimaryKey() .'='. $primaryVal;
        $originUrlKey = isset($one['url_key']) ? $one['url_key'] : '';
        $defaultLangTitle = Yii::$service->fecshoplang->getDefaultLangAttrVal($one['name'], 'name');
        $urlKey = Yii::$service->url->saveRewriteUrlKeyByStr($defaultLangTitle, $originUrl, $originUrlKey);
        $model->url_key = $urlKey;
        $model->save();
    
        return $model;
    }
    
}
