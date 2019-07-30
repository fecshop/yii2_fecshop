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

/**
 * block catalog/productinfo.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    public $_saveUrl;
    protected $_attr;
    protected $_custom_option_list_str;
    /**
     * 为了可以使用rewriteMap，use 引入的文件统一采用下面的方式，通过Yii::mapGet()得到className和Object
     */
    protected $_attrBlockName = '\fecshop\app\appadmin\modules\Catalog\block\productinfo\index\Attr';
    protected $_attrBlock;

    public function init()
    {
        /**
         * 通过Yii::mapGet() 得到重写后的class类名以及对象。Yii::mapGet是在文件@fecshop\yii\Yii.php中
         */
        $this->_attrBlockName = Yii::mapGetName($this->_attrBlockName);  
        
        $this->_saveUrl = CUrl::getUrl('catalog/productinfo/managereditsave');
        parent::init();
        $product_id = $this->_param[$this->_primaryKey];
        if($product_id){
            // 从mysql中取出来qty。
            $qty = Yii::$service->product->stock->getProductFlatQty($product_id);
            $this->_one['qty'] = $qty ;
        }
        
        $this->_attr = new $this->_attrBlockName($this->_one);
        //$this->_param		= $request_param[$this->_editFormData];
    }

    public function setService()
    {
        $this->_service = Yii::$service->product;
    }

    public function getCurrentProductPrimay()
    {
        $primaryKey = Yii::$service->product->getPrimaryKey();
        $primaryVal = CRequest::param($primaryKey);
        if ($primaryVal) {
            return $primaryKey.'='.$primaryVal;
        }

        return '';
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        return [
            'baseInfo'          => $this->getBaseInfo(),
            'priceInfo'         => $this->getPriceInfo(),
            'tier_price'        => $this->_one['tier_price'],
            'metaInfo'          => $this->getMetaInfo(),
            'groupAttr'         => $this->getGroupAttr(),
            'descriptionInfo'   => $this->getDescriptionInfo(),
            'attrGroup'         => $this->_attr->getProductAttrGroupSelect(),
            'primaryInfo'       => $this->getCurrentProductPrimay(),
            'img_html'          => $this->getImgHtml(),
            'custom_option'     => $this->_one['custom_option'],
            'product_id'        => $this->_one[Yii::$service->product->getPrimaryKey()],
            //'editBar' 	    => $this->getEditBar(),
            //'textareas'	    => $this->_textareas,
            //'lang_attr'	    => $this->_lang_attr,
            'saveUrl'           => $this->_saveUrl,
            'operate'           => Yii::$app->request->get('operate'),
            'custom_option_add' => $this->getCustomOptionAdd(),
            'custom_option_img' => $this->getCustomOpImgHtml(),
            'custom_option_list'=> $this->_custom_option_list_str,
            'relation'          => $this->getRelationInfo(),
        ];
    }

    public function getCustomOptionAdd()
    {
        $attr_group = $this->_one['attr_group'];
        $currentAttrGroup = CRequest::param('attr_group');
        if ($currentAttrGroup) {
            $attr_group = $currentAttrGroup;
        }
        
        $str = '';
        $this->_custom_option_list_str = '';
        if ($attr_group) {
            $custom_option_attr_info = Yii::$service->product->getCustomOptionAttrInfo($attr_group);
            if (is_array($custom_option_attr_info) && !empty($custom_option_attr_info)) {
                $this->_custom_option_list_str .= '<table style=""><thead><tr>';

                foreach ($custom_option_attr_info as $attr => $info) {
                    $label = $info['label'];
                    $this->_custom_option_list_str .= '<th>' . Yii::$service->page->translate->__($attr) . '</th>';

                    $str .= '<div class="nps"><span >' . Yii::$service->page->translate->__($attr) . ':</span>';
                    $type = isset($info['display']['type']) ? $info['display']['type'] : '';
                    $data = isset($info['display']['data']) ? $info['display']['data'] : '';
                    if ($type == 'select' && is_array($data) && !empty($data)) {
                        $str .= '<select atr="'.$attr.'" class="custom_option_attr">';
                        foreach ($info['display']['data'] as $v) {
                            $str .= '<option value="'.$v.'">' . Yii::$service->page->translate->__($v) . '</option>';
                        }
                        $str .= '</select>';
                    }
                    $str .= '</div>';
                }
                $str .= '<div class="nps"><span>Sku:</span><input style="width:40px;" type="text" class="custom_option_sku"  value="" /></div>
						<div class="nps"><span>Qty:</span><input style="width:40px;" type="text" class="custom_option_qty"  value="" /></div>
						<div class="nps"><span>Price:</span><input  style="width:40px;" type="text" class="custom_option_price"  value="" /></div>
						<div class="nps" style="width:220px;"><a class=" button chose_custom_op_img" style="display: block;float: left; margin: -2px 10px 0;" ><span style="margin:0">' . Yii::$service->page->translate->__('Select Image') . '</span></a><div class="chosened_img"></div></div>
						<div class="nps"><a style="display: block;float: right; margin: -2px 10px 0;" class="button add_custom_option"><span style="margin:0">+</span></a></div>
					';

                $this->_custom_option_list_str .= '<th>' . Yii::$service->page->translate->__('sku') . '</th><th>' . Yii::$service->page->translate->__('qty') . '</th><th>' . Yii::$service->page->translate->__('price') . '</th><th>' . Yii::$service->page->translate->__('image') . '</th><th>' . Yii::$service->page->translate->__('delete') . '</th>';
                $this->_custom_option_list_str .= '<tr><thead>';
                //$this->_custom_option_list_str .= '<tbody></tbody>';
                //$this->_custom_option_list_str .= '</table>';
                $this->_custom_option_list_str .= '<tbody>';
                //echo '<br><br>';
                
                $custom_option = $this->_one['custom_option'];
                if (is_array($custom_option) && !empty($custom_option)) {
                    foreach ($custom_option  as $one) {
                        $this->_custom_option_list_str .= '<tr>';
                        foreach ($custom_option_attr_info as $attr => $info) {
                            $val = $one[$attr];
                            $this->_custom_option_list_str .= '<td rel="'.$attr.'" val="'.$val.'">' . Yii::$service->page->translate->__($val) . '</td>';
                        }
                        $this->_custom_option_list_str .= '<td class="custom_option_sku" rel="sku" val="'.$one['sku'].'">'.$one['sku'].'</td>';
                        $this->_custom_option_list_str .= '<td rel="qty" val="'.$one['qty'].'">'.$one['qty'].'</td>';
                        $this->_custom_option_list_str .= '<td rel="price" val="'.$one['price'].'">'.$one['price'].'</td>';
                        $this->_custom_option_list_str .= '<td rel="image" ><img style="width:30px;" rel="'.$one['image'].'" src="'.Yii::$service->product->image->getUrl($one['image']).'"/></td>';
                        $this->_custom_option_list_str .= '<td><a title="' . Yii::$service->page->translate->__('delete') . '"  href="javascript:void(0)" class="btnDel deleteCustomList"><i class="fa fa-trash-o"></i></a></td>';
                        $this->_custom_option_list_str .= '</tr>';
                    }
                }
                $this->_custom_option_list_str .= '</tbody>';
                $this->_custom_option_list_str .= '</table>';
            }
        }

        return $str;
    }

    public function getRelationInfo()
    {
        $this->_lang_attr = '';
        $this->_textareas = '';
        $editArr = $this->_attr->getRelationInfo();
        $editBar = $this->getEditBar($editArr);

        return $this->_lang_attr.$editBar.$this->_textareas;
    }

    public function getBaseInfo()
    {
        $this->_lang_attr = '';
        $this->_textareas = '';
        $editArr = $this->_attr->getBaseInfo();
        $editBar = $this->getEditBar($editArr);

        return $this->_lang_attr.$editBar.$this->_textareas;
    }

    public function getPriceInfo()
    {
        $this->_lang_attr = '';
        $this->_textareas = '';
        $editArr = $this->_attr->getPriceInfo();
        $editBar = $this->getEditBar($editArr);

        return $this->_lang_attr.$editBar.$this->_textareas;
    }

    public function getMetaInfo()
    {
        $this->_lang_attr = '';
        $this->_textareas = '';
        $editArr = $this->_attr->getMetaInfo();
        $editBar = $this->getEditBar($editArr);

        return $this->_lang_attr.$editBar.$this->_textareas;
    }

    public function getDescriptionInfo()
    {
        $this->_lang_attr = '';
        $this->_textareas = '';
        $editArr = $this->_attr->getDescriptionInfo();
        $editBar = $this->getEditBar($editArr);

        return $this->_lang_attr.$editBar.$this->_textareas;
    }
    
    public function getGroupAttr()
    {
        $this->_lang_attr = '';
        $this->_textareas = '';
        $editArr = $this->_attr->getGroupAttr();
        $this->_primaryKey = $this->_service->getPrimaryKey();
        $product_id = $this->_param[$this->_primaryKey];
        $this->_one = $this->_service->getByPrimaryKey($product_id);
        if($product_id){
            // 从mysql中取出来qty。
            $qty = Yii::$service->product->stock->getProductFlatQty($product_id);
            $this->_one['qty'] = $qty ;
            // 从mysql中取出来custom option qty
            $co_qty_arr     = Yii::$service->product->stock->getProductCustomOptionQty($product_id);
            //var_dump($co_qty_arr);
            $custom_option  = $this->_one['custom_option'];
            if(is_array($co_qty_arr) && is_array($custom_option)){
                $arr = [];
                foreach($custom_option as $custom_option_sku => $one){
                    $custom_option[$custom_option_sku]['qty'] = isset($co_qty_arr[$custom_option_sku]) ? $co_qty_arr[$custom_option_sku] : 0;
                }
                $this->_one['custom_option'] = $custom_option;
            }
        }
        // add translate
        if (!empty($editArr) && is_array($editArr)) {
            foreach ($editArr as $k => $v) {
                $editArr[$k]['label'] = Yii::$service->page->translate->__($k);
                if (isset($v['display']['type']) && in_array($v['display']['type'], ['select', 'editSelect'])) {
                    if (isset($v['display']['data']) && is_array($v['display']['data'])) {
                        $select_data = [];
                        foreach ($v['display']['data'] as $v2) {
                            if ($v['display']['type'] == 'select') {
                                $select_data[$v2] = Yii::$service->page->translate->__($v2);
                            } else {
                                $select_data[$v2] = $v2;
                            }
                        }
                        $editArr[$k]['display']['data'] = $select_data;
                    }
                }    
            }
            $editBar = $this->getEditBar($editArr);

            return $this->_lang_attr.$editBar.$this->_textareas;
        }

        return '';
    }
    
    public function getVal($name, $column){
        if (isset ($this->_one[$name]) ) {
            
            return ($this->_one[$name] || $this->_one[$name] === 0) ? $this->_one[$name] : $column['default'];
        } else if(isset($this->_one['attr_group_info']) && $this->_one['attr_group_info']) { //  mysql model类型
            $attr_group_info = $this->_one['attr_group_info'];
            if (isset($attr_group_info[$name])) {
                return $attr_group_info[$name];
            } else {
                return '';
            }
        }
    }  

    public function getImgHtml()
    {
        if (isset($this->_one['image']['main']) && !empty($this->_one['image']['main'])) {
            $main_image = $this->_one['image']['main'];
        }
        if (isset($this->_one['image']['gallery']) && !empty($this->_one['image']['gallery'])) {
            $gallery_image = $this->_one['image']['gallery'];
        }
        $str =
        '<div>
			<table class="list productimg" width="100%" >
				<thead>
					<tr>
						<td>' . Yii::$service->page->translate->__('Image') . '</td>
						<td>' . Yii::$service->page->translate->__('Label') . '</td>
						<td>' . Yii::$service->page->translate->__('Sort Order') . '</td>
                        <td>' . Yii::$service->page->translate->__('Main Image') . '</td>
                        <td>' . Yii::$service->page->translate->__('Window Img') . '</td>
						<td>' . Yii::$service->page->translate->__('Description Img') . '</td>
                        <td>' . Yii::$service->page->translate->__('Delete') . '</td>
					</tr>
				</thead>
				<tbody>';
        if (!empty($main_image) && is_array($main_image)) {
            $is_thumbnails = $main_image['is_thumbnails'] ? $main_image['is_thumbnails'] : 1;
            $is_detail = $main_image['is_detail'] ? $main_image['is_detail'] : 1;
            
            $str .= '<tr class="p_img" rel="1" style="border-bottom:1px solid #ccc;">
						<td style="width:120px;text-align:center;"><img  rel="'.$main_image['image'].'" style="width:100px;height:100px;" src="'.Yii::$service->product->image->getUrl($main_image['image']).'"></td>
						<td style="width:220px;text-align:center;"><input  type="text" class="image_label" name="image_label"  value="'.$main_image['label'].'" /></td>
						<td style="width:220px;text-align:center;"><input  type="text" class="sort_order"  name="sort_order" value="'.$main_image['sort_order'].'"  /></td>
						<td style="width:30px;text-align:center;"><input type="radio" name="image" checked  value="'.$main_image['image'].'" /></td>
						
                        <td style="width:220px;text-align:center;">
                            <select name="is_thumbnails" class="is_thumbnails">
                                '.$this->getYesNoOptions($is_thumbnails).'
                            </select>
                        </td>
                        <td style="width:220px;text-align:center;">
                            <select name="is_detail" class="is_detail">
                                '.$this->getYesNoOptions($is_detail).'
                            </select>
                        </td>
                        
                        <td style="padding:0 0 0 20px;"><a class="delete_img btnDel" href="javascript:void(0)"><i class="fa fa-trash-o"></i></a></td>
					</tr>';
        }
        if (!empty($gallery_image) && is_array($gallery_image)) {
            $i = 2;
            
            foreach ($gallery_image as $gallery) {
                $is_thumbnails = $gallery['is_thumbnails'] ? $gallery['is_thumbnails'] : 1;
                $is_detail = $gallery['is_detail'] ? $gallery['is_detail'] : 1;
            
                $str .= '<tr class="p_img" rel="'.$i.'" style="border-bottom:1px solid #ccc;">
									<td style="width:120px;text-align:center;"><img  rel="'.$gallery['image'].'" style="width:100px;height:100px;" src="'.Yii::$service->product->image->getUrl($gallery['image']).'"></td>
									<td style="width:220px;text-align:center;"><input  type="text" class="image_label" name="image_label"  value="'.$gallery['label'].'" /></td>
									<td style="width:220px;text-align:center;"><input  type="text" class="sort_order"  name="sort_order" value="'.$gallery['sort_order'].'"  /></td>
									<td style="width:30px;text-align:center;"><input type="radio" name="image"   value="'.$gallery['image'].'" /></td>
                                   <td style="width:220px;text-align:center;">
                                        <select name="is_thumbnails" class="is_thumbnails">
                                            '.$this->getYesNoOptions($is_thumbnails).'
                                        </select>
                                    </td>
                                    <td style="width:220px;text-align:center;">
                                        <select name="is_detail" class="is_detail">
                                            '.$this->getYesNoOptions($is_detail).'
                                        </select>
                                    </td>
									<td style="padding:0 0 0 20px;"><a class="delete_img btnDel" href="javascript:void(0)"><i class="fa fa-trash-o"></i></a></td>
								</tr>';
                $i++;
            }
        }

        $str .= '</tbody>
			</table>
		</div>';

        return $str;
    }
    
    public function getYesNoOptions($val){
        if($val == 1){
            return '
                <option  value="1" selected="selected" >' . Yii::$service->page->translate->__('Yes') . '</option>
                <option  value="2">' . Yii::$service->page->translate->__('No') . '</option>
            ';
        }else{
            return '
                <option  value="1">' . Yii::$service->page->translate->__('Yes') . '</option>
                <option  value="2" selected="selected">' . Yii::$service->page->translate->__('No') . '</option>
            ';
        }
    }

    public function getCustomOpImgHtml()
    {
        if (isset($this->_one['image']['main']) && !empty($this->_one['image']['main'])) {
            $main_image = $this->_one['image']['main'];
        }
        if (isset($this->_one['image']['gallery']) && !empty($this->_one['image']['gallery'])) {
            $gallery_image = $this->_one['image']['gallery'];
        }
        $str = '';
        if (!empty($main_image) && is_array($main_image)) {
            $str .= '<span><img  rel="'.$main_image['image'].'" style="width:80px;" src="'.Yii::$service->product->image->getUrl($main_image['image']).'"></span>';
        }
        if (!empty($gallery_image) && is_array($gallery_image)) {
            $i = 2;
            foreach ($gallery_image as $gallery) {
                $str .= '<span><img  rel="'.$gallery['image'].'" style="width:80px;" src="'.Yii::$service->product->image->getUrl($gallery['image']).'"></span>';
                $i++;
            }
        }
        $str .= '';

        return $str;
    }

    public function getEditArr()
    {
    }

    /**
     * save article data,  get rewrite url and save to article url key.
     */
    public function save()
    {
        $this->initParamType();
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        // var_dump()

        if (Yii::$app->request->post('operate') == 'copy') {
            $productPrimaryKey = Yii::$service->product->getPrimaryKey();
            if (isset($this->_param[$productPrimaryKey])) {
                unset($this->_param[$productPrimaryKey]);
                //echo 111;
                //var_dump($this->_param);
                //exit;
            }
        }
        
        $this->_service->save($this->_param, 'catalog/product/index');
        
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message'    => Yii::$service->page->translate->__('Save Success'),
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => $errors,
            ]);
            exit;
        }
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
                if(is_array($option) && !empty($option)){
                    foreach($option as $key => $val){
                        if($key == 'qty'){
                            $option[$key] = (int) $option[$key];
                        } else if ($key == 'price') {
                            $option[$key] = (float) $option[$key];
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
                foreach ($category  as $cate) {
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
                        list($gallery_image, $gallery_label, $gallery_sort_order,$gallery_is_thumbnails,$gallery_is_detail) = explode('#####', $one);
                        $save_gallery[] = [
                            'image'            => $gallery_image,
                            'label'              => $gallery_label,
                            'sort_order'      => $gallery_sort_order,
                            'is_thumbnails' => $gallery_is_thumbnails,
                            'is_detail'         => $gallery_is_detail,
                        ];
                    }
                }
                $this->_param['image']['gallery'] = $save_gallery;
            }
        }
        // init image main
        if ($image_main) {
            list($main_image, $main_label, $main_sort_order,$main_is_thumbnails,$main_is_detail) = explode('#####', $image_main);
            $save_main = [
                'image'             => $main_image,
                'label'               => $main_label,
                'sort_order'       => $main_sort_order,
                'is_thumbnails'   => $main_is_thumbnails,
                'is_detail'          => $main_is_detail,
            ];
            $this->_param['image']['main'] = $save_main;
        }
        //qty
        $this->_param['qty'] = $this->_param['qty'] ? (float) ($this->_param['qty']) : 0;
        $this->_param['package_number'] = (int)abs($this->_param['package_number']);
        //is_in_stock
        $this->_param['is_in_stock'] = $this->_param['is_in_stock'] ? (int) ($this->_param['is_in_stock']) : 0;
        //price
        $this->_param['cost_price'] = $this->_param['cost_price'] ? (float) ($this->_param['cost_price']) : 0;
        $this->_param['price'] = $this->_param['price'] ? (float) ($this->_param['price']) : 0;
        $this->_param['special_price'] = $this->_param['special_price'] ? (float) ($this->_param['special_price']) : 0;
        //date
        $this->_param['new_product_from'] = $this->_param['new_product_from'] ? (float) (strtotime($this->_param['new_product_from'])) : 0;
        $this->_param['new_product_to'] = $this->_param['new_product_to'] ? (float) (strtotime($this->_param['new_product_to'])) : 0;
        $this->_param['special_from'] = $this->_param['special_from'] ? (float) (strtotime($this->_param['special_from'])) : 0;
        $this->_param['special_to'] = $this->_param['special_to'] ? (float) (strtotime($this->_param['special_to'])) : 0;
        //weight
        $this->_param['weight'] = $this->_param['weight'] ? (float) ($this->_param['weight']) : 0;
        //长
        $this->_param['long'] = $this->_param['long'] ? (float) ($this->_param['long']) : 0;
        //宽
        $this->_param['width'] = $this->_param['width'] ? (float) ($this->_param['width']) : 0;
        //高
        $this->_param['high'] = $this->_param['high'] ? (float) ($this->_param['high']) : 0;
        //体积重
        $this->_param['volume_weight'] = Yii::$service->shipping->getVolumeWeight($this->_param['long'], $this->_param['width'], $this->_param['high']) ;
        
        $this->_param['score'] = $this->_param['score'] ? (int) ($this->_param['score']) : 0;
        //status
        $this->_param['status'] = $this->_param['status'] ? (float) ($this->_param['status']) : 0;
        //image main sort order
        if (isset($this->_param['image']['main']['sort_order']) && !empty($this->_param['image']['main']['sort_order'])) {
            $this->_param['image']['main']['sort_order'] = (int) ($this->_param['image']['main']['sort_order']);
        }
        //image gallery
        if (isset($this->_param['image']['gallery']) && is_array($this->_param['image']['gallery']) && !empty($this->_param['image']['gallery'])) {
            $gallery_af = [];
            foreach ($this->_param['image']['gallery'] as $gallery) {
                if (isset($gallery['sort_order']) && !empty($gallery['sort_order'])) {
                    $gallery['sort_order'] = (int) $gallery['sort_order'];
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
                                        $this->_param[$attr][$langAttr] = (int) $this->_param[$attr][$langAttr];
                                    }
                                }
                            }
                        } else {
                            $this->_param[$attr] = (int) $this->_param[$attr];
                        }
                    }
                    if ($dbtype == 'Float') {
                        if (isset($attrInfo['display']['lang']) && $attrInfo['display']['lang']) {
                            $langs = Yii::$service->fecshoplang->getAllLangCode();
                            if (is_array($langs) && !empty($langs)) {
                                foreach ($langs as $langCode) {
                                    $langAttr = Yii::$service->fecshoplang->getLangAttrName($attr, $langCode);
                                    if (isset($this->_param[$attr][$langAttr]) && $this->_param[$attr][$langAttr]) {
                                        $this->_param[$attr][$langAttr] = (float) $this->_param[$attr][$langAttr];
                                    }
                                }
                            }
                        } else {
                            $this->_param[$attr] = (float) $this->_param[$attr];
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
                        $tier_qty = (int) $tier_qty;
                        $tier_price = (float) $tier_price;
                        $tier_price_arr[] = [
                            'qty'    => $tier_qty,
                            'price'    => $tier_price,
                        ];
                    }
                }
            }
        }
        $tier_price_arr = \fec\helpers\CFunc::array_sort($tier_price_arr, 'qty', 'asc');
        $this->_param['tier_price'] = $tier_price_arr;
    }

    // 批量删除
    public function delete()
    {
        $ids = '';
        if ($id = CRequest::param($this->_primaryKey)) {
            $ids = $id;
        } elseif ($ids = CRequest::param($this->_primaryKey.'s')) {
            $ids = explode(',', $ids);
        }
        $this->_service->remove($ids);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message' => Yii::$service->page->translate->__('Remove Success'),
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode' => '300',
                'message' => $errors,
            ]);
            exit;
        }
    }
}
