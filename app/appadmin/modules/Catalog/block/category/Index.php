<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\block\category;

use fec\helpers\CRequest;
use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        //$this->_saveUrl = CUrl::getUrl('catalog/category/managereditsave');
        parent::init();
    }

    public function remove()
    {
        $primaryKey = Yii::$service->category->getPrimaryKey();
        $primaryVal = Yii::$app->request->get($primaryKey);
        if ($primaryVal) {
            Yii::$service->category->remove($primaryVal);
            $errors = Yii::$service->helper->errors->get();
            if (!$errors) {
                echo  json_encode([
                    'statusCode'=>'200',
                    'message'=>'delete success',
                ]);
                exit;
            } else {
                echo  json_encode([
                    'statusCode'=>'300',
                    'message'=>$errors,
                ]);
                exit;
            }
        }
        echo  json_encode([
            'statusCode'=>'300',
            'message'=>'您需要选择一个分类',
        ]);
        exit;
    }

    public function getCategoryTree($treeArr = '', $level = 0)
    {
        if (!$treeArr) {
            $treeArr = Yii::$service->category->getTreeArr();
        }
        $id = Yii::$service->category->GetPrimaryKey();

        if (!$level) {
            $str = '<ul class="tree treeFolder">';
        } else {
            $str = '<ul>';
        }

        if (is_array($treeArr) && !empty($treeArr)) {
            foreach ($treeArr as $one) {
                $idVal = $one[$id];
                $name = $one['name'];
                $str .= '<li><a class="category_one" key="'.$idVal.'" rel="jbsxBox" target="ajax" href="'.CUrl::getUrl('catalog/category/index', [$id=>$idVal]).'" >'.$name.'</a>';
                if (isset($one['child']) && !empty($one['child'])) {
                    $str .= $this->getCategoryTree($one['child'], 1);
                }
                $str .= '</li>';
            }
        }
        $str .= '</ul>';

        return $str;
        /*
        <ul class="tree treeFolder">
                <li><a href="javascript:void(0);">11</a>
                    <ul>
                        <li><a href="javascript:void(0);" >22</a></li>
                        <li><a href="javascript:void(0);" >33</a></li>
                        <li><a href="javascript:void(0);" >44</a></li>
                        <li><a href="javascript:void(0);" >55</a></li>
                    </ul>
                </li>

             </ul>
        */
    }

    public function saveCategory()
    {
        $cate_id = Yii::$service->category->GetPrimaryKey();
        $editFormData = Yii::$app->request->post('editFormData');
        if (isset($editFormData)) {
            $defaultLangName = Yii::$service->fecshoplang->GetDefaultLangAttrName('name');
            if (!isset($editFormData['name'][$defaultLangName])
                || !($editFormData['name'][$defaultLangName])
            ) {
                echo  json_encode([
                    'statusCode'=>'300',
                    'message'=>'分类的默认语言name不能为空',
                ]);
                exit;
            }
            $thumbnail_image = CRequest::param('thumbnail_image');
            $image = CRequest::param('image');
            if ($thumbnail_image) {
                $editFormData['thumbnail_image'] = $thumbnail_image;
            }
            if ($image) {
                $editFormData['image'] = $image;
            }

            $product_select_info = CRequest::param('product_select_info');
            $product_unselect_info = CRequest::param('product_unselect_info');
            $category_id = $editFormData[$cate_id];
            //var_dump($editFormData);
            //echo $category_id;exit;
            $addCateProductIdArr = explode(',', $product_select_info);
            $deleteCateProductIdArr = explode(',', $product_unselect_info);
            Yii::$service->product->addAndDeleteProductCategory($category_id, $addCateProductIdArr, $deleteCateProductIdArr);

            $parent_id = (Yii::$app->request->post('parent_id'));
            if (!isset($editFormData[$cate_id]) || !$editFormData[$cate_id]) {
                $editFormData['parent_id'] = $parent_id;
            }
            $originUrlKey = 'catalog/category/index';
            Yii::$service->category->save($editFormData, $originUrlKey);
            $errors = Yii::$service->helper->errors->get();
            if (!$errors) {
                echo  json_encode([
                    'statusCode'=>'200',
                    'message'=>'save success',
                ]);
                exit;
            } else {
                echo  json_encode([
                    'statusCode'=>'300',
                    'message'=>$errors,
                ]);
                exit;
            }
        }
    }

    public function getLastInfo()
    {
        $primaryKey = Yii::$service->category->getPrimaryKey();
        $primaryVal = Yii::$app->request->get($primaryKey);

        return [
            'thumbnail_image'    => $this->_one['thumbnail_image'],
            'image'            => $this->_one['image'],
            'thumbnail_imageurl'=> Yii::$service->category->image->getUrl($this->_one['thumbnail_image']),
            'imageurl'            => Yii::$service->category->image->getUrl($this->_one['image']),
            'product_url'        => CUrl::getUrl('catalog/category/product', [$primaryKey => $primaryVal]),
            'base_info'        => $this->getBaseInfo(),
            'meta_info'        => $this->getMetaInfo(),
        ];
    }

    public function getLastData()
    {
        $this->saveCategory();

        return [
            'base_info' => $this->getBaseInfo(),
            'meta_info' => $this->getMetaInfo(),
            'save_url'    => CUrl::getUrl('catalog/category/index'),
            'category_tree' => $this->getCategoryTree(),
        ];
    }

    public function getMetaInfo()
    {
        $this->_lang_attr = '';
        $this->_textareas = '';
        $editArr = [
            [
                'label'=>'Title',
                'name'=>'title',
                'display'=>[
                    'type' => 'inputString',
                    'lang' => true,
                ],
                'require' => 0,
            ],
            [
                'label'=>'Meta Keywords',
                'name'=>'meta_keywords',
                'display'=>[
                    'type' => 'inputString',
                    'lang' => true,
                ],
                'require' => 0,
            ],
            [
                'label'=>'Meta Description',
                'name'=>'meta_description',
                'display'=>[
                    'type' => 'textarea',
                    'lang' => true,
                    'rows'    => 14,
                    'cols'    => 100,
                ],
                'require' => 0,
            ],

        ];

        return $this->getEditBar($editArr).$this->_lang_attr.$this->_textareas;
    }

    public function getBaseInfo()
    {
        $this->_lang_attr = '';
        $this->_textareas = '';
        $editArr = [
            [
                'label'=>'分类名字',
                'name'=>'name',
                'display'=>[
                    'type' => 'inputString',
                    'lang' => true,
                ],
                'require' => 1,
            ],

            [
                'label'=>'分类状态',
                'name'=>'status',
                'display'=>[
                    'type' => 'select',
                    'data' => [
                        1    => '激活',
                        2    => '关闭',
                    ],
                ],
                'require' => 1,
                'default' => 1,
            ],
            
            [
                'label'=>'Menu是否显示',
                'name'=>'menu_show',
                'display'=>[
                    'type' => 'select',
                    'data' => [
                        1    => '菜单中显示',
                        2    => '菜单中不显示',
                    ],
                ],
                'require' => 1,
                'default' => 1,
            ],

            [
                'label'=>'Url Key',
                'name'=>'url_key',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],

            [
                'label'=>'分类产品过滤属性',
                'name'=>'filter_product_attr_selected',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],

            [
                'label'=>'分类产品非过滤属性',
                'name'=>'filter_product_attr_unselected',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label'=>'分类描述',
                'name'=>'description',
                'display'=>[
                    'type' => 'textarea',
                    'lang' => true,
                    'rows'    => 14,
                    'cols'    => 100,
                ],
                'require' => 0,
            ],

            [
                'label'=>'菜单自定义部分',
                'name'=>'menu_custom',
                'display'=>[
                    'type' => 'textarea',
                    'lang' => true,
                    'rows'    => 14,
                    'cols'    => 100,
                ],
                'require' => 0,
            ],

        ];
        $str = $this->getEditBar($editArr);

        return $this->_lang_attr.$str.$this->_textareas;
    }

    public function setService()
    {
        $this->_service = Yii::$service->category;
    }

    /**
     * config edit array.
     */
    public function getEditArr()
    {
    }
}
