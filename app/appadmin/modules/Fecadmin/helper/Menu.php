<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Fecadmin\helper;
use fecadmin\models\AdminMenu;
use fec\helpers\CUrl;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Menu{

    public static function getContent(){
        $menuArr = Yii::$service->page->adminMenu->getConfigMenu();

        return self::getLeftMenuTreeHtml($menuArr);
    }

    public static function getRoleUrlKey(){
        return Yii::$service->admin->role->getCurrentRoleResources();
    }

    # 得到后台显示菜单（左侧）
    public static function getLeftMenuTreeHtml($treeArr='',$i=1){
        //$active_menu_ids = $this->getActiveMenuIds();
        $str = '';
        //if(!$treeArr){
        //    $treeArr = $this->getMenuTreeArray();
        //}
        foreach($treeArr as $node){
            $name = $node["label"];
            $url_key = $node["url_key"];
            $roleUrlKeys = self::getRoleUrlKey();
            //var_dump($roleUrlKeys);exit;
            if($url_key && (!isset($roleUrlKeys[$url_key]) || !$roleUrlKeys[$url_key])){
                continue;
            }

            if($i == 1){
                $str .=	'<div class="accordionHeader">
							<h2><span>Folder</span>'.$name .'</h2>
						</div>
						<div class="accordionContent">';
                if(self::hasChild($node)){
                    $str .='<ul class="tree treeFolder">';
                    $str .= self::getLeftMenuTreeHtml($node['child'],$i+1);
                    $str .='</ul>';
                }
                $str .=	'</div>';
            }else{
                if(self::hasChild($node)){
                    //$str .=		'<li><a href="'.CUrl::getUrl($url_key).'" target="navTab" rel="page1">'.$name.'</a>';
                    $str .=		'<li><a href="javascript:void(0)" >'.$name.'</a>';
                    $str .=			'<ul>';
                    $str .= self::getLeftMenuTreeHtml($node['child'],$i+1);
                    $str .=			'</ul>';
                    $str .=		'</li>';
                }else{
                    $str .='<li><a href="'.CUrl::getUrl($url_key).'" target="navTab" rel="page1">'.$name.'</a></li>';
                }
            }
        }
        return $str;
    }


    public static function hasChild($node){
        if(isset($node['child']) && !empty($node['child'])){
            return true;
        }
        return false;
    }


}



?>