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
    public function getConfigMenu($menu='')
    {
        if (empty($menu)) {
            $menu = $this->menuConfig;
        }
        /**
         * 进行sort_order排序，以及enable处理
         */
        if (is_array($menu) && !empty($menu)) {
            $menu = $this->arraySortAndRemoveDisableMenu($menu, 'sort_order', 'desc');
            foreach ($menu as $k=>$one) {
                if (isset($one['child']) && is_array($one['child']) && !empty($one['child'])) {
                    $menu[$k]['child'] = $this->getConfigMenu($one['child']);
                }
            }
        }

        return $menu;
    }
    /**
     * 排序菜单函数，并且去掉status值为false的子项
     */
    public function arraySortAndRemoveDisableMenu($array, $keys, $dir='asc')
    {  
		$keysvalue = $new_array = array();  
		foreach ($array as $k=>$v){  
            // 如果enable设置值为false，则代表隐藏掉该菜单
            if (isset($v['enable']) && $v['enable'] === false) {
                continue;
            }
			$keysvalue[$k] = isset($v[$keys]) ? $v[$keys] : 0; 
		}  
		if($dir == 'asc'){  
			asort($keysvalue);  
		}else{  
			arsort($keysvalue);  
		}  
		reset($keysvalue);  
		foreach ($keysvalue as $k=>$v){  
			$new_array[$k] = $array[$k];  
		}  
		return $new_array;  
	}
    
    public function getLeftMenuHtml()
    {
        $menuArr = $this->getConfigMenu();

        return $this->getLeftMenuTreeHtml($menuArr);
    }

    public function getRoleUrlKey()
    {
        
        return Yii::$service->admin->role->getCurrentRoleResources();
    }
    /**
     * @param $nodeL | array, 菜单结点
     * @param $roleUrlKeys | array, 权限urlKey数组
     *  查看：当前节点的所有的子节点以及子子节点（递归）的urlKey，是否存在于$roleUrlKeys，只要存在一个，就返回true
     *  此函数的作用为：查看当前阶段是否存在有权限的子菜单，如果没有，则返回false，当前菜单也将隐藏
     */
    public function hasChildRoleUrlKey($nodeL, &$roleUrlKeys)
    {
        if (!$this->hasChild($nodeL)) {
            
            return false;
        }
        $treeArr = $nodeL['child'];
        if (!is_array($treeArr)) {
            
            return false;
        }
        foreach ($treeArr as $node) {
            $url_key = $node["url_key"];
            if ($url_key) {
                // 如果存在有权限的urlKey
                if (isset($roleUrlKeys[$url_key]) && $roleUrlKeys[$url_key]) {
                    
                    return true;
                }
            } else if($this->hasChild($node)) {
                // 如果子菜单存在有权限的url Key，返回true
                if ($this->hasChildRoleUrlKey($node, $roleUrlKeys)) {
                    
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * @param $treeArr | array，菜单数组
     * @param $i | int， 菜单层级
     * 得到后台显示菜单（左侧）
     */
    public function getLeftMenuTreeHtml($treeArr='', $i=1)
    {
        $str = '';
        foreach ($treeArr as $node) {
            // 二次开发的过程中，如果fecshop后台的某些菜单想不显示，那么可以在配置中将active设置成false
            if (isset($node['active']) && $node['active'] === false) {
                
                continue;
            }
            $name = Yii::$service->page->translate->__($node["label"]);
            $url_key = $node["url_key"];
            $roleUrlKeys = $this->getRoleUrlKey();
            if ($url_key) {
                if (!isset($roleUrlKeys[$url_key]) || !$roleUrlKeys[$url_key]) {
                    
                    continue;
                }
            } else if (!$this->hasChildRoleUrlKey($node, $roleUrlKeys)) {  // 查看所有的子菜单，是否存在某个urlkey存在于$roleUrlKeys
                
                continue;
            }
            if ($i == 1) {
                $str .=	'<div class="accordionHeader">
							<h2><span>Folder</span>'.$name .'
                                <span class="first_collapsable"></span>
                            </h2>
						</div>
						<div class="accordionContent">';
                if ($this->hasChild($node)) {
                    $str .='<ul class="tree treeFolder">';
                    $str .= $this->getLeftMenuTreeHtml($node['child'],$i+1);
                    $str .='</ul>';
                }
                $str .=	'</div>';
            }else{
                if ($this->hasChild($node)) {
                    //$str .=		'<li><a href="'.CUrl::getUrl($url_key).'" target="navTab" rel="page1">'.$name.'</a>';
                    $str .=		'<li><a href="javascript:void(0)" >'.$name.'</a>';
                    $str .=			'<ul>';
                    $str .= $this->getLeftMenuTreeHtml($node['child'],$i+1);
                    $str .=			'</ul>';
                    $str .=		'</li>';
                } else {
                    $str .= '<li><a href="'.CUrl::getUrl($url_key).'" target="navTab" rel="page1">'.$name.'</a></li>';
                }
            }
        }
        
        return $str;
    }

    public function hasChild($node)
    {
        if (isset($node['child']) && !empty($node['child'])) {
            
            return true;
        }
        
        return false;
    }
    
}
