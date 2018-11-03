<?php
/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\admin;

use fec\helpers\CUrl;
use fecshop\services\Service;
use Yii;

/**
 * Page Menu services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Menu extends Service
{
    /**
     * @var array 后台菜单配置, 参看@fecshop/config/services/Page.php的配置
     */
    public $menuConfig;
    
    /**
     * @return Array , 得到后台菜单配置。
     */
    public function getConfigMenu(){
        $menu = $this->menuConfig;

        return $menu;
    }
    
    public function getLeftMenuHtml(){
        $menuArr = $this->getConfigMenu();

        return $this->getLeftMenuTreeHtml($menuArr);
    }

    public function getRoleUrlKey(){
        
        return Yii::$service->admin->role->getCurrentRoleResources();
    }

    # 得到后台显示菜单（左侧）
    public function getLeftMenuTreeHtml($treeArr='', $i=1){
        $str = '';
        foreach($treeArr as $node){
            $name = $node["label"];
            $url_key = $node["url_key"];
            $roleUrlKeys = $this->getRoleUrlKey();
            if($url_key && (!isset($roleUrlKeys[$url_key]) || !$roleUrlKeys[$url_key])){
                continue;
            }
            if($i == 1){
                $str .=	'<div class="accordionHeader">
							<h2><span>Folder</span>'.$name .'</h2>
						</div>
						<div class="accordionContent">';
                if($this->hasChild($node)){
                    $str .='<ul class="tree treeFolder">';
                    $str .= $this->getLeftMenuTreeHtml($node['child'],$i+1);
                    $str .='</ul>';
                }
                $str .=	'</div>';
            }else{
                if($this->hasChild($node)){
                    //$str .=		'<li><a href="'.CUrl::getUrl($url_key).'" target="navTab" rel="page1">'.$name.'</a>';
                    $str .=		'<li><a href="javascript:void(0)" >'.$name.'</a>';
                    $str .=			'<ul>';
                    $str .= $this->getLeftMenuTreeHtml($node['child'],$i+1);
                    $str .=			'</ul>';
                    $str .=		'</li>';
                }else{
                    $str .='<li><a href="'.CUrl::getUrl($url_key).'" target="navTab" rel="page1">'.$name.'</a></li>';
                }
            }
        }
        
        return $str;
    }

    public function hasChild($node){
        if(isset($node['child']) && !empty($node['child'])){
            
            return true;
        }
        
        return false;
    }
    
}
