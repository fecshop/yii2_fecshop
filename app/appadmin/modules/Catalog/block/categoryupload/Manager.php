<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\block\categoryupload;

use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use Yii;
use yii\base\BaseObject;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manager extends BaseObject
{
    
    public $_fileFullDir;
    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        /*
         * service component, data provider
         */
        // parent::init();
    }

    public function getLastData()
    {
        return [
        ];
    }
    
    
    public function uploadCategory()
    {
        $fileFullDir = Yii::getAlias('@appadmin/runtime/upload/');
        if ($this->saveUploadExcelFile($fileFullDir)) {
            //echo $this->_fileFullDir;
            
            $arr = \fec\helpers\CExcel::getExcelContent($this->_fileFullDir);
            $i = 0;
            if (is_array($arr) && !empty($arr)) {
                foreach ($arr as $one) {
                    $i++;
                    if ($i > 1) {
                        $saveStatus = $this->saveCategory($one);
                        if (!$saveStatus) {
                            $errorMessage = Yii::$service->helper->errors->get(',');
                            echo  json_encode([
                                'statusCode' => '300',
                                'message'    => $errorMessage,
                            ]);
                            exit;
                        }
                    }
                }
            }
            echo  json_encode([
                'statusCode' => '200',
                'message'    => Yii::$service->page->translate->__('Save Success'),
            ]);
            exit;
        }
        
    }
    
    public function saveCategory($one)
    {
        // 从excel中获取数据。
        $category_id = $one['A'];
        if (!$category_id) {
            return true;  // 没有分类的行，直接跳过
        }
        $parent_id = $one['B'];
        $language_code = $one['C'];
        $category_name = $one['D'];
        $status = $one['E'];
        $menu_show = $one['F'];
        $url_key = $one['G'];
        $description = $one['H'];
        $title = $one['I'];
        $meta_keywords = $one['J'];
        $meta_description = $one['K'];
        $image = $one['L'];
        $thumbnail_image = $one['M'];
        
        
        $nameLang = Yii::$service->fecshoplang->getLangAttrName('name',$language_code);
        $titleLang = Yii::$service->fecshoplang->getLangAttrName('title',$language_code);
        $metaKeywordsLang = Yii::$service->fecshoplang->getLangAttrName('meta_keywords',$language_code);
        $metaDescriptionLang = Yii::$service->fecshoplang->getLangAttrName('meta_description',$language_code);
        $descriptionLang = Yii::$service->fecshoplang->getLangAttrName('description',$language_code);
        
        $categoryPrimaryKey = Yii::$service->category->getPrimaryKey();
        //$categoryMode = Yii::$service->category->getByPrimarykey($category_id);
        //$categoryPrimaryKey = Yii::$service->category->getPrimaryKey();
        //$category_id = '';
        //if ($categoryMode && isset($categoryMode[$categoryPrimaryKey]) && $categoryMode[$categoryPrimaryKey]) {
        //    $category_id = $categoryMode[$categoryPrimaryKey];
        //}
        
        $categoryArr[$categoryPrimaryKey] = $category_id;
        $categoryArr['parent_id'] = $parent_id;
        $categoryArr['name'][$nameLang] = $category_name;
        $categoryArr['status'] = (int)$status;
        $categoryArr['menu_show'] = (int)$menu_show;
        $categoryArr['url_key'] = $url_key;
        $categoryArr['description'][$descriptionLang] = $description;
        $categoryArr['title'][$titleLang] = $title;
        $categoryArr['meta_keywords'][$metaKeywordsLang] = $meta_keywords;
        $categoryArr['meta_description'][$metaDescriptionLang] = $meta_description;
        $categoryArr['image'] = $image;
        $categoryArr['thumbnail_image'] = $thumbnail_image;
        
        return Yii::$service->category->excelSave($categoryArr);
    }
    
    # 1.保存前台上传的文件。
	public function saveUploadExcelFile($fileFullDir){
        
        $name = $_FILES["file"]["name"];
        $fileFullDir .= 'category_'.time().'_'.rand(1000,9999);
        if(strstr($name,'.xlsx')){
            $fileFullDir .='.xlsx';
        } else if (strstr($name,'.xls')){
            $fileFullDir .='.xls';
        }  
        $this->_fileFullDir  = $fileFullDir;    
        $result = @move_uploaded_file($_FILES["file"]["tmp_name"],$fileFullDir);
        
		return $result;
	}
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
