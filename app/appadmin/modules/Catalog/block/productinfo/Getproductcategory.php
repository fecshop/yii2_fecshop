<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\block\productinfo;

use fec\helpers\CRequest;
use Yii;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Getproductcategory
{
    public function getProductCategory()
    {
        $product_id = CRequest::param('product_id');

        $menuStr = $this->getMenuStr($product_id);
        echo json_encode([
            'menu'=>$menuStr,
            'return_status'=>'success',
        ]);
    }

    public function getMenuStrChild($categorys, $category_ids)
    {
        $str = '<ul>';
        foreach ($categorys as $id=>$category) {
            $name = $category['name'];
            $str .= '<li><a '.(in_array($id, $category_ids) ? 'checked="true"' : '').'  tname="'.$name.'" tvalue="'.$id.'">'.$name.'</a>';
            if (is_array($category['child']) && !empty($category['child'])) {
                $str .= $this->getMenuStrChild($category['child'], $category_ids);
            }
        }
        $str .= '</ul>';

        return $str;
    }

    public function getMenuStr($product_id)
    {

        //$menu = $this->getMenArray($product_id);
        $menu = Yii::$service->category->getTreeArr();
        $category_ids = $this->getCategoryByProductId($product_id);
        $str = '';
        if (is_array($menu) && !empty($menu)) {
            foreach ($menu as $id=>$category) {
                $name = $category['name'];
                $str .= '<li><a '.(in_array($id, $category_ids) ? 'checked="true"' : '').'  tname="'.$name.'" tvalue="'.$id.'">'.$name.'</a>';
                if (is_array($category['child']) && !empty($category['child'])) {
                    $str .= $this->getMenuStrChild($category['child'], $category_ids);
                }
                $str .= '</li>';
            }
        }

        return $str;
    }

    // 得到產品的分類id
    public function getCategoryByProductId($product_id)
    {
        $product = Yii::$service->product->getByPrimaryKey($product_id);
        if (isset($product['category']) && !empty($product['category']) && is_array($product['category'])) {
            return $product['category'];
        }

        return [];
    }

    public function getMenu($product_id)
    {
        //设置在config设置的值

        //echo $this->_modelName;exit;
        $query = $this->getModelQuery('catalog_category');
        $count = $query->count();
        $product_coll = $query->all();
        if (!($count)) {
            //echo $this->_modelName;
            //echo $product_coll->count;exit;
            $model = $this->getModel('catalog_category');

            $store_array = Store::getAllStoreArrayOnly();
            $insertData = [];
            $insertData['_id'] = 1;
            $insertData['parent_id'] = 0;
            $name_arr = [];
            foreach ($store_array as $sn) {
                $name_arr[$sn.'_name'] = 'Root_'.$sn;
            }
            $insertData['name'] = $name_arr;
            $model->save($insertData);
            $query = $this->getModelQuery('catalog_category');
            $count = $query->count();
            $product_coll = $query->all();
        }

        $this->_menu = $product_coll;
    }

    //website
    public function getMenArray($product_id)
    {
        $this->getMenu($product_id);
        $root_catefory = '';
        foreach ($this->_menu as $root_cate) {
            if ($root_cate['parent_id'] == 0) {
                $root_catefory = $root_cate;
                break;
            }
        }
        //echo 2;
        $menu = [];
        //echo $this->getCurrentWebsite();exit;
        $default_name = Store::getStoreDefaultName();

        $root_id = $root_catefory['_id'];
        $menu[$root_id] = ['name'=>$root_catefory['name'][$default_name]];
        //var_dump($menu);exit;
        //echo 3;
        foreach ($this->_menu as $lev_1) {
            if ($lev_1['parent_id'] == $root_id) {

                //$level1_arr[$lev_1['_id']] = ['name'=>$lev_1['name'][$default_name ]];
                $lev_1_id = $lev_1['_id'];
                $menu[$root_id]['child'][$lev_1_id] = ['name'=>$lev_1['name'][$default_name]];
                $parent_id_1 = $lev_1['_id'];
                foreach ($this->_menu as $lev_2) {
                    //echo $lev_2['parent_id'].$parent_id_1."#";
                    if ($lev_2['parent_id'] == $parent_id_1) {
                        $lev_2_id = $lev_2['_id'];
                        $menu[$root_id]['child'][$lev_1_id]['child'][$lev_2_id] = ['name'=>$lev_2['name'][$default_name]];
                        $parent_id_2 = $lev_2['_id'];
                        foreach ($this->_menu as $lev_3) {
                            if ($lev_3['parent_id'] == $parent_id_2) {
                                //var_dump($lev_3);
                                $lev_3_id = $lev_3['_id'];
                                $menu[$root_id]['child'][$lev_1_id]['child'][$lev_2_id]['child'][$lev_3_id] = ['name'=>$lev_3['name'][$default_name]];
                                $parent_id_3 = $lev_3['_id'];
                            }
                        }
                    }
                }
            }
        }
        //echo 4;
        //exit;
        //var_dump($menu);exit;
        return $menu;
    }
}
