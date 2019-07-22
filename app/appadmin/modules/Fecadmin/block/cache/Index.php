<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Fecadmin\block\cache;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use Yii;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends AppadminbaseBlock implements AppadminbaseBlockInterface
{
    // 各个入口的redis配置和common的redis配置，合并，最后存放到该类变量中
    public $appRedisCache;
    // 报错信息
    public $errors; 
    // 从模块配置中取出来 common的redis配置的数组key。
    public $commonConfigKey = 'commonConfig';
    
	public function init()
    {
		$this->_currentUrl = CUrl::getUrl("fecadmin/cache/index");
        $this->_currentParamUrl = CUrl::getCurrentUrl();
    }
	public function getLastData(){
		# 返回数据的函数
		# 隐藏部分
		$pagerForm = $this->getPagerForm();  
		# 搜索部分
		$searchBar = $this->getSearchBar();
		# 编辑 删除  按钮部分
		$editBar = $this->getEditBar();
		# 表头部分
		$thead = $this->getTableThead();
		# 表内容部分
		$tbody = $this->getTableTbody();
		# 分页部分
		$toolBar = $this->getToolBar($this->_param['numCount'],$this->_param['pageNum'],$this->_param['numPerPage']); 
		
		return [
			'pagerForm'	 	=> $pagerForm,
			'searchBar'		=> $searchBar,
			'editBar'		=> $editBar,
			'thead'		=> $thead,
			'tbody'		=> $tbody,
			'toolBar'	=> $toolBar,
		];
	}
    public function getTableFieldArr(){
        return [];
    }
	# 定义搜索部分字段格式
	public function getSearchArr(){
		return [];
	}
	
	public function getEditBar(){
		if(!strstr($this->_currentParamUrl,"?")){
			$csvUrl = $this->_currentParamUrl."?type=export";
		}else{
			$csvUrl = $this->_currentParamUrl."&type=export";
		}

		return '<ul class="toolBar">
					<li><a csrfName="' .CRequest::getCsrfName(). '" csrfVal="' .CRequest::getCsrfValue(). '" title="'.Yii::$service->page->translate->__('Are you sure you want to refresh the cache?').'" target="selectedTodo" rel="ids" postType="string" href="'.$this->_currentUrl.'?method=reflush" class="edit"><span>' . Yii::$service->page->translate->__('Refresh Cache') . '</span></a></li>
					<li class="line">line</li>
				</ul>';
	}
	
	public function getTableThead(){
		return '
			<thead>
				<tr>
					<th width="22"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
					<th width="40">' . Yii::$service->page->translate->__('Cache Name')  . '</th>
					<th width="110">' . Yii::$service->page->translate->__('Cache Description') . '</th>
				</tr>
			</thead>';
	}
	
	public function getTableTbody(){
		$str = '';
        $reflushRedisCache = \Yii::$app->controller->module->params['cacheConfigFile'];
        if (is_array($reflushRedisCache)) {
            foreach ($reflushRedisCache as $appName => $c) {
                if ($appName != $this->commonConfigKey) {
                    $str .= '<tr target="sid_user" rel="'.$appName.'">
                        <td><input name="ids" value="'.$appName.'" type="checkbox"></td>
                        <td>'.$appName.'</td>
                        <td> '. Yii::$service->page->translate->__('Refresh all caches') .'：'.$appName.'</td>
                    </tr>
                    ';
                }
            }
        }
		
		return	$str;
	}
    /**
     * 根据模块的配置部分，得到各个入口的redis的配置
     * 原理：根据配置中指定的各个入口的redis所在的配置文件，取出来各个入口的redis配置，
     *       然后和common的redis配置合并，得到入口最终的redis配置，然后实例化redis component，然后清空redis缓存
     */
    public function getRedisCacheConfig(){
        $arr = \Yii::$app->controller->module->params['cacheConfigFile'];
        if (is_array($arr)) {
            // 加载common公用基础redis配置
            if (!isset($arr[$this->commonConfigKey]) || !$arr[$this->commonConfigKey]) {
                $this->errors = 'module config: cacheConfigFile[commonConfig] can not empty';
                
                return false;
            }
            $file = Yii::getAlias($arr[$this->commonConfigKey]);
            $config = require($file);
            if (!isset($config['components']['cache']['class']) || !$config['components']['cache']['class'] || $config['components']['cache']['class'] != 'yii\redis\Cache') {
                $this->errors = 'can not find  $config[\'components\'][\'cache\'][\'redis\'] in '.$file;
                
                return false;
            }
            
            $baseConfig = isset($config['components']['redis']) ? $config['components']['redis'] : [];
            // 加载各个入口的redis配置
            foreach ($arr as $app => $appFile) {
                if ($app != $this->commonConfigKey) {
                    $file = Yii::getAlias($appFile);
                    $config = require($file);
                    $appRedisConfig = isset($config['components']['redis']) ? $config['components']['redis'] : [];
                    if (!empty($appRedisConfig)) {
                        $this->appRedisCache[$app] = \yii\helpers\ArrayHelper::merge($baseConfig, $appRedisConfig);
                    } else {
                        $this->appRedisCache[$app] = $baseConfig;
                    
                    }
                }
            }
            $baseCacheConfig = isset($config['components']['cache']['redis']) ? $config['components']['cache']['redis'] : [];
            foreach ($this->appRedisCache as $app => $config) {
                $this->appRedisCache[$app] = \yii\helpers\ArrayHelper::merge($config, $baseCacheConfig);
            }
            
            
            // 加载各个入口的redis配置
            foreach ($arr as $app => $appFile) {
                if ($app != $this->commonConfigKey) {
                    $file = Yii::getAlias($appFile);
                    $config = require($file);
                    $appRedisConfig = isset($config['components']['cache']['redis']) ? $config['components']['cache']['redis'] : [];
                    if (!empty($appRedisConfig)) {
                        $this->appRedisCache[$app] = \yii\helpers\ArrayHelper::merge($this->appRedisCache[$app], $appRedisConfig);
                    }
                }
            }
        }
        
        
        return true;
    }
    // 判断是否是文件cache
    public function isFileCache()
    {
        $arr = \Yii::$app->controller->module->params['cacheConfigFile'];
        if (is_array($arr)) {
            // 加载common公用基础redis配置
            if (!isset($arr[$this->commonConfigKey]) || !$arr[$this->commonConfigKey]) {
                $this->errors = 'module config: cacheFileConfigFile[commonConfig] can not empty';
                
                return false;
            }
            $file = Yii::getAlias($arr[$this->commonConfigKey]);
            $config = require($file);
            if (!isset($config['components']['cache']['class']) || !$config['components']['cache']['class'] || $config['components']['cache']['class'] != 'yii\caching\FileCache') {
                $this->errors = 'can not find  $config[\'components\'][\'cache\'][\'fileCache\'] in '.$file;
                
                return false;
            }
            
            return true;
        }
        
        return false;
    }
    
    // 刷新文件缓存
    public function flushFileCache()
    {
        $cachePath = Yii::$app->cache->cachePath;
        $cacheAppNameStr = Yii::$app->request->post('ids');
        $cacheAppNameArr = explode(",",$cacheAppNameStr);
        $successReflushAppNameArr = [];
        if (is_array($cacheAppNameArr)) {
            foreach ($cacheAppNameArr as $cacheAppName) {
                $cacheAppName = strtolower($cacheAppName);
                $appCachePath = str_replace('appadmin', $cacheAppName, $cachePath);
                //echo $cachePath;
                Yii::$app->cache->cachePath = $appCachePath;
                Yii::$app->cache->flush();
                $successReflushAppNameArr[] = $cacheAppName;
            }
        }
        
         echo  json_encode([
            "statusCode" => "200",
            "message" => Yii::$service->page->translate->__('Reflush cache success, AppName') . ":" . implode(',', $successReflushAppNameArr),
        ]);
    }
    
    /**
     * 清空选择的入口的所有缓存。
     */
	public function reflush(){
        // 如果是文件缓存
        if ($this->isFileCache()) {
            $this->flushFileCache();
            exit;
        }
        // flush redis cache
        $this->flushRedisCache();
        exit;
    }
    
    // 刷新redis缓存
    public function flushRedisCache()
    {
        
        // redis cache
        if (!$this->getRedisCacheConfig()) {
            echo  json_encode([
                "statusCode"    => "300",
                "message"       => $this->errors,
            ]);
            return ;
        }
        
        $successReflushAppNameArr = [];
		$cacheAppNameStr = Yii::$app->request->post('ids');
		$cacheAppNameArr = explode(",",$cacheAppNameStr);
        if (is_array($cacheAppNameArr)) {
            foreach ($cacheAppNameArr as $cacheAppName) {
                $cacheAppName = trim($cacheAppName);
                if (isset($this->appRedisCache[$cacheAppName]) && $this->appRedisCache[$cacheAppName]) {
                    
                    $redisComponent = Yii::createObject($this->appRedisCache[$cacheAppName]);
                    $redisComponent->executeCommand('FLUSHDB');
                    $successReflushAppNameArr[] = $cacheAppName;
                }
            }
        }
		# 刷新 配置 缓存
		// \fecadmin\helpers\CConfig::flushCacheConfig();
        echo  json_encode([
            "statusCode" => "200",
            "message" => Yii::$service->page->translate->__('Reflush cache success, AppName') . ":" . implode(',', $successReflushAppNameArr),
        ]);
    }
}






