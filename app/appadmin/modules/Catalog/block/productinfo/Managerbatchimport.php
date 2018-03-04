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
use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
//use fecshop\app\appadmin\modules\Catalog\block\productinfo\index\Attr;
use Yii;
use PHPExcel_IOFactory;

/**
 * block catalog/productinfo.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Managerbatchimport extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    public function setService()
    {
        $this->_service = Yii::$service->product;
    }

    public function getEditArr()
    {
    }

    public function import()
    {
//        $base_path=Yii::getAlias("@webroot");
        //文件存放的路径
        $save_path = "../uploads/product/";
//        Yii::info(Yii::getAlias("@webroot"), 'fecshop_debug');
        if (!file_exists($save_path))//判断文件夹是否存在
        {
            mkdir($save_path);
        }
//        Yii::info($save_path, 'fecshop_debug');
        //文件存放的文件夹
//        $save_files = $this->geturl();
        $file_path = $_FILES['file_upload']["name"];
        //这个是上传文件到需要保存的位置，
        if (!@move_uploaded_file($_FILES['file_upload']["tmp_name"], $save_path . $_FILES['file_upload']["name"])) {
            $error = "error|上传文件错误.";
            $result = [
                'statusCode' => 300,
                'message' => $error,
            ];
            return json_encode($result);
        }
        $file_name = $save_path . $file_path;

        $objReader = PHPExcel_IOFactory::createReader('Excel2007');

        $objPHPExcel = $objReader->load($file_name, 'utf-8');
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数

        for ($j = 2; $j <= $highestRow; $j++) {
            $name = trim($objPHPExcel->getActiveSheet()->getCell("A" . $j)->getValue());
            $spu = trim($objPHPExcel->getActiveSheet()->getCell("B" . $j)->getValue());
            $sku = trim($objPHPExcel->getActiveSheet()->getCell("C" . $j)->getValue());
            $qty = trim($objPHPExcel->getActiveSheet()->getCell("D" . $j)->getValue());
            $oriPrice = trim($objPHPExcel->getActiveSheet()->getCell("E" . $j)->getValue());
            $salePrice = trim($objPHPExcel->getActiveSheet()->getCell("F" . $j)->getValue());
            $saleSpecPrice = trim($objPHPExcel->getActiveSheet()->getCell("G" . $j)->getValue());
            $saleSpecStart = trim($objPHPExcel->getActiveSheet()->getCell("H" . $j)->getValue());
            $saleSpecEnd = trim($objPHPExcel->getActiveSheet()->getCell("I" . $j)->getValue());
            $shortDesc = trim($objPHPExcel->getActiveSheet()->getCell("J" . $j)->getValue());
            $desc = trim($objPHPExcel->getActiveSheet()->getCell("K" . $j)->getValue());
            $mainPic = trim($objPHPExcel->getActiveSheet()->getCell("L" . $j)->getValue());
            $category = trim($objPHPExcel->getActiveSheet()->getCell("M" . $j)->getValue());
            $attr_group = trim($objPHPExcel->getActiveSheet()->getCell("N" . $j)->getValue());
            $custom_option = trim($objPHPExcel->getActiveSheet()->getCell("O" . $j)->getValue());
            $newStart = trim($objPHPExcel->getActiveSheet()->getCell("P" . $j)->getValue());
            $newEnd = trim($objPHPExcel->getActiveSheet()->getCell("Q" . $j)->getValue());
//            Yii::info($pics, 'fecshop_debug');

//            Yii::info(json_decode($a), 'fecshop_debug');
            $data['name'] = ['name_en' => $name];
            $data['spu'] = $spu;
            $data['sku'] = $sku;
            $data['status'] = 2;
            $data['qty'] = $qty ? $qty : 0;
            $data['is_in_stock'] = 1;
            $data['cost_price'] = $oriPrice ? $oriPrice : 0;//成本价
            $data['price'] = $salePrice ? $salePrice : 0;//销售价
            $data['special_price'] = $saleSpecPrice ? $saleSpecPrice : 0;
            $data['special_from'] = $saleSpecStart ? strtotime($saleSpecStart) : 0;
            $data['special_to'] = $saleSpecEnd ? strtotime($saleSpecEnd) : 0;
            $data['tier_price'] = [];//批发价格
            $data['short_description'] = ['short_description_en' => $shortDesc];
            $data['description'] = ['description_en' => $desc];
            $data['attr_group'] = $attr_group;
            $data['custom_option'] = [];
            $data['category'] = $category ? $category : [];
            //todo 图片上传、自定义属性组、多语言支持
            $data['image'] = [
//                'gallery' => isset($galleryPic) ? $galleryPic : [],
                'main' => [
                    'image' => $mainPic,
                    'label' => '',
                    'sort_order' => '',
                    'is_thumbnails' => 1,
                    'is_detail' => 1,
                ]
            ];
            $data['new_product_from'] = $newStart ? strtotime($newStart) : 0;
            $data['new_product_to'] = $newEnd ? strtotime($newEnd) : 0;
            Yii::info(json_encode($data), 'fecshop_debug');
            $this->_service->save($data, 'catalog/product/index');

            $errors = Yii::$service->helper->errors->get();
            if ($errors) {
                $result = [
                    'statusCode' => 300,
                    'message' => $errors,
                ];
                return json_encode($result);
            }
        }
        $result = [
            'statusCode' => 200,
            'message' => 'success',
            'navTabId' => '',
            'rel' => '',
            'callbackType' => 'closeCurrent',
            'forwardUrl' => '',
        ];
        return json_encode($result);
    }

    function geturl()
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');

        $str = $year . $month . $day;
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $path = getcwd() . "/upload/" . $str;
        } else {
            $path = "/mnt/upload/" . $str;
        }
        if (!file_exists($path))//判断文件夹是否存在
        {
            mkdir($path);
        }

        //return $path."/";
        return $str . "/";
    }


    protected function initParamType()
    {
        $request_param = CRequest::param();
        $this->_param = $request_param[$this->_editFormData];
        $this->_param['attr_group'] = CRequest::param('attr_group');
        $custom_option = CRequest::param('custom_option');
        //var_dump($custom_option);
        $custom_option = $custom_option ? json_decode($custom_option, true) : [];
        $custom_option_arr = [];
        if (is_array($custom_option) && !empty($custom_option)) {
            foreach ($custom_option as $option) {
                if (is_array($option) && !empty($option)) {
                    foreach ($option as $key => $val) {
                        if ($key == 'qty') {
                            $option[$key] = (int)$option[$key];
                        } else if ($key == 'price') {
                            $option[$key] = (float)$option[$key];
                        } else {
                            $option[$key] = html_entity_decode($val);
                        }
                    }
                }
                $custom_option_arr[$option['sku']] = $option;
            }
        }

        $this->_param['custom_option'] = $custom_option_arr;
        //var_dump($this->_param['custom_option']);
        $image_gallery = CRequest::param('image_gallery');
        $image_main = CRequest::param('image_main');
        $save_gallery = [];
        // Category
        $category = CRequest::param('category');
        if ($category) {
            $category = explode(',', $category);
            if (!empty($category)) {
                $cates = [];
                foreach ($category as $cate) {
                    if ($cate) {
                        $cates[] = $cate;
                    }
                }
                $this->_param['category'] = $cates;
            } else {
                $this->_param['category'] = [];
            }
        } else {
            $this->_param['category'] = [];
        }
        // init image gallery
        if ($image_gallery) {
            $image_gallery_arr = explode('|||||', $image_gallery);
            if (!empty($image_gallery_arr)) {
                foreach ($image_gallery_arr as $one) {
                    if (!empty($one)) {
                        list($gallery_image, $gallery_label, $gallery_sort_order, $gallery_is_thumbnails, $gallery_is_detail) = explode('#####', $one);
                        $save_gallery[] = [
                            'image' => $gallery_image,
                            'label' => $gallery_label,
                            'sort_order' => $gallery_sort_order,
                            'is_thumbnails' => $gallery_is_thumbnails,
                            'is_detail' => $gallery_is_detail,
                        ];
                    }
                }
                $this->_param['image']['gallery'] = $save_gallery;
            }
        }
        // init image main
        if ($image_main) {
            list($main_image, $main_label, $main_sort_order, $main_is_thumbnails, $main_is_detail) = explode('#####', $image_main);
            $save_main = [
                'image' => $main_image,
                'label' => $main_label,
                'sort_order' => $main_sort_order,
                'is_thumbnails' => $main_is_thumbnails,
                'is_detail' => $main_is_detail,
            ];
            $this->_param['image']['main'] = $save_main;
        }
        //qty
        $this->_param['qty'] = $this->_param['qty'] ? (float)($this->_param['qty']) : 0;
        $this->_param['package_number'] = (int)abs($this->_param['package_number']);
        //is_in_stock
        $this->_param['is_in_stock'] = $this->_param['is_in_stock'] ? (int)($this->_param['is_in_stock']) : 0;
        //price
        $this->_param['cost_price'] = $this->_param['cost_price'] ? (float)($this->_param['cost_price']) : 0;
        $this->_param['price'] = $this->_param['price'] ? (float)($this->_param['price']) : 0;
        $this->_param['special_price'] = $this->_param['special_price'] ? (float)($this->_param['special_price']) : 0;
        //date
        $this->_param['new_product_from'] = $this->_param['new_product_from'] ? (float)(strtotime($this->_param['new_product_from'])) : 0;
        $this->_param['new_product_to'] = $this->_param['new_product_to'] ? (float)(strtotime($this->_param['new_product_to'])) : 0;
        $this->_param['special_from'] = $this->_param['special_from'] ? (float)(strtotime($this->_param['special_from'])) : 0;
        $this->_param['special_to'] = $this->_param['special_to'] ? (float)(strtotime($this->_param['special_to'])) : 0;
        //weight
        $this->_param['weight'] = $this->_param['weight'] ? (float)($this->_param['weight']) : 0;
        //长
        $this->_param['long'] = $this->_param['long'] ? (float)($this->_param['long']) : 0;
        //宽
        $this->_param['width'] = $this->_param['width'] ? (float)($this->_param['width']) : 0;
        //高
        $this->_param['high'] = $this->_param['high'] ? (float)($this->_param['high']) : 0;
        //体积重
        $this->_param['volume_weight'] = Yii::$service->shipping->getVolumeWeight($this->_param['long'], $this->_param['width'], $this->_param['high']);


        $this->_param['score'] = $this->_param['score'] ? (int)($this->_param['score']) : 0;
        //status
        $this->_param['status'] = $this->_param['status'] ? (float)($this->_param['status']) : 0;
        //image main sort order
        if (isset($this->_param['image']['main']['sort_order']) && !empty($this->_param['image']['main']['sort_order'])) {
            $this->_param['image']['main']['sort_order'] = (int)($this->_param['image']['main']['sort_order']);
        }
        //image gallery
        if (isset($this->_param['image']['gallery']) && is_array($this->_param['image']['gallery']) && !empty($this->_param['image']['gallery'])) {
            $gallery_af = [];
            foreach ($this->_param['image']['gallery'] as $gallery) {
                if (isset($gallery['sort_order']) && !empty($gallery['sort_order'])) {
                    $gallery['sort_order'] = (int)$gallery['sort_order'];
                }
                $gallery_af[] = $gallery;
            }
            $this->_param['image']['gallery'] = $gallery_af;
        }
        // 自定义属性 也就是在 @common\config\fecshop_local_services\Product.php 产品服务的 customAttrGroup 配置的产品属性。
        $custom_attr = \Yii::$service->product->getGroupAttrInfo($this->_param['attr_group']);
        if (is_array($custom_attr) && !empty($custom_attr)) {
            foreach ($custom_attr as $attrInfo) {
                $attr = $attrInfo['name'];
                $dbtype = $attrInfo['dbtype'];
                if (isset($this->_param[$attr]) && !empty($this->_param[$attr])) {
                    if ($dbtype == 'Int') {
                        if (isset($attrInfo['display']['lang']) && $attrInfo['display']['lang']) {
                            $langs = Yii::$service->fecshoplang->getAllLangCode();
                            if (is_array($langs) && !empty($langs)) {
                                foreach ($langs as $langCode) {
                                    $langAttr = Yii::$service->fecshoplang->getLangAttrName($attr, $langCode);
                                    if (isset($this->_param[$attr][$langAttr]) && $this->_param[$attr][$langAttr]) {
                                        $this->_param[$attr][$langAttr] = (int)$this->_param[$attr][$langAttr];
                                    }
                                }
                            }
                        } else {
                            $this->_param[$attr] = (int)$this->_param[$attr];
                        }
                    }
                    if ($dbtype == 'Float') {
                        if (isset($attrInfo['display']['lang']) && $attrInfo['display']['lang']) {
                            $langs = Yii::$service->fecshoplang->getAllLangCode();
                            if (is_array($langs) && !empty($langs)) {
                                foreach ($langs as $langCode) {
                                    $langAttr = Yii::$service->fecshoplang->getLangAttrName($attr, $langCode);
                                    if (isset($this->_param[$attr][$langAttr]) && $this->_param[$attr][$langAttr]) {
                                        $this->_param[$attr][$langAttr] = (float)$this->_param[$attr][$langAttr];
                                    }
                                }
                            }
                        } else {
                            $this->_param[$attr] = (float)$this->_param[$attr];
                        }
                    }
                }
            }
        }

        //tier price
        $tier_price = $this->_param['tier_price'];
        $tier_price_arr = [];
        if ($tier_price) {
            $arr = explode('||', $tier_price);
            if (is_array($arr) && !empty($arr)) {
                foreach ($arr as $ar) {
                    list($tier_qty, $tier_price) = explode('##', $ar);
                    if ($tier_qty && $tier_price) {
                        $tier_qty = (int)$tier_qty;
                        $tier_price = (float)$tier_price;
                        $tier_price_arr[] = [
                            'qty' => $tier_qty,
                            'price' => $tier_price,
                        ];
                    }
                }
            }
        }
        $tier_price_arr = \fec\helpers\CFunc::array_sort($tier_price_arr, 'qty', 'asc');
        $this->_param['tier_price'] = $tier_price_arr;
    }

}
