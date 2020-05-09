<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\extension;

//use fecshop\models\mysqldb\cms\StaticBlock;
use Yii;
use fecshop\services\Service;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class RemoteService extends Service
{
    const ADDONS_TOKEN = 'addons_access_token';
    
    public $remoteUrl = 'http://addons.server.fecmall.com';
    public $loginUrlKey = '/customer/login/account';
    public $getAddonsListUrlKey = '/customer/addons/index';
    public $getAddonInfoUrlKey = '/customer/addons/info';
    public $getDeveloperInfoUrlKey = '/customer/addons/developer';
    // 远程登陆
    public function login($param) 
    {
        $url = $this->remoteUrl . $this->loginUrlKey ;
        $data = [
            'email' => $param['email'],
            'password' => $param['password'],
        ];
        list($responseHeader, $result) = $this->getCurlData($url, 'post', [], $data, 30);
        if ($result['code'] == 200) {
            $access_token =  $responseHeader['Access-Token'];
            $this->setAccessToken($access_token);
            
            return true;
        }
        
        return false;
    }
    
    // 得到开发者的信息
    public function getDeveloperInfo()
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            
            return false;
        }
        $url = $this->remoteUrl . $this->getDeveloperInfoUrlKey ;
        $headerRequest = [
            'access-token: '.$accessToken,
        ];
        list($responseHeader, $result) = $this->getCurlData($url, 'post', $headerRequest, [], 30);
        
        if ($result['code'] == 200) {
            
            return $result['data'];
        }
        
        return false;
    }
    
    // 得到远程的addon 信息(我的应用列表)
    public function getMyAddonsInfo($pageNum, $numPerPage)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            
            return false;
        }
        $url = $this->remoteUrl . $this->getAddonsListUrlKey ;
        $headerRequest = [
            'access-token: '.$accessToken,
        ];
        $data = [
            'pageNum' => $pageNum,
            'numPerPage' => $numPerPage,
        ];
        list($responseHeader, $result) = $this->getCurlData($url, 'post', $headerRequest, $data, 30);
        if ($result['code'] == 200) {
            
            return $result['data'];
        }
        
        return false;
    }
    // 得到应用的详细信息。
    public function getAddonsInfoByNamespace($namespace)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            
            return false;
        }
        $url = $this->remoteUrl . $this->getAddonInfoUrlKey ;
        $headerRequest = [
            'access-token: '.$accessToken,
        ];
        $data = [
            'namespace' => $namespace,
        ];
        list($responseHeader, $result) = $this->getCurlData($url, 'post', $headerRequest, $data, 30);
        if ($result['code'] == 200) {
            
            return $result['data'];
        }
        
        return false;
    }
    // 应用zip文件报错的文件路径
    public function getExtensionZipFilePath($packageName, $folderName)
    {
        $filePath = Yii::getAlias('@addons/'.$packageName.'/'.$folderName.'/'.$folderName.'.zip');
        
        return $filePath;
    }
    
    // 下载应用
    public function downloadAddons($namespace, $packageName, $folderName, $addonName)
    {
        // 得到下载的url
        $url = $this->remoteUrl . '/customer/addons/download?namespace='.$namespace;
        // 当前应用的package，进行mkdir，然后chomod 777
        $packagePath = Yii::getAlias('@addons/'.$packageName);
        if (!is_dir($packagePath)){
            mkdir($packagePath);
            chmod($packagePath, 0777);
        }
        // 应用文件夹
        $packagePath = Yii::getAlias('@addons/'.$packageName.'/'.$folderName);
        if (!is_dir($packagePath)){
            mkdir($packagePath);
            chmod($packagePath, 0777);
        }
        $filePath = $this->getExtensionZipFilePath($packageName, $folderName);
        // 根据文件路径，以及addon的name，得到zip文件存放的文件完整路径
        //$filePath = Yii::getAlias('@addons/'.$packageName.'/'.$folderName.'/'.$folderName.'.zip');
        // 将url中的zip文件，存储到该文件目录。
        if ($this->downCurl($url,$filePath)) {
            
            return $filePath;
        } 
        
        return null;
    }
    
    // 远程下载zip包
    function downCurl($url, $filePath)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            
            return false;
        }
        $headerRequest = [
            'access-token: '.$accessToken,
        ];
        //初始化
        $ch = curl_init();
        curl_setopt($ch, 
            CURLOPT_HTTPHEADER, 
            $headerRequest
            );  
        //设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        //打开文件描述符
        $fp = fopen ($filePath, 'w+');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        //这个选项是意思是跳转，如果你访问的页面跳转到另一个页面，也会模拟访问。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch,CURLOPT_TIMEOUT, 5000);
        //执行命令
        curl_exec($ch);
        //关闭URL请求
        curl_close($ch);
        //关闭文件描述符
        fclose($fp);
        
        return true;
    }
    
    public function isLogin($checkRemote = false)
    {
        if ($checkRemote) {
            // 进行远程检查
        }
        if ($this->getAccessToken()) {
            
            return true;
        }
        
        return false;
    }
    
    public function setAccessToken($access_token)
    {
        if (!$access_token) {
            return false;
        }
        return Yii::$app->session->set(self::ADDONS_TOKEN, $access_token);
    }
    
    public function getAccessToken()
    {
        return Yii::$app->session->get(self::ADDONS_TOKEN);
    }
    
    public static function getCurlData($url,$type="get", $headerData, $data=array(),$timeout = 30){
        //对空格进行转义
        $url = str_replace(' ','+',$url);
        if ($type == "get") {
            if (!empty($data) && is_array($data)) {
                $arr = [];
                foreach ($data as $k=>$v) {
                    $arr[] = $k."=".$v;
                }
                $str  = implode("&",$arr);
                if (strstr($url,"?")) {
                    $url .= "&".$str;
                } else {
                    $url .= "?".$str;
                }
            }
        }
        $data = json_encode($data);
        $headerRequest = [
        'Accept: application/json',
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
        ];
        $headerRequest = array_merge($headerRequest, $headerData);
        $url = urldecode($url);
        //echo $url ;exit;
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);  //定义超时3秒钟  
        if ($type == "post") {
            // POST数据
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, 
                CURLOPT_HTTPHEADER, 
                $headerRequest
                );
            // 把post的变量加上
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        //执行并获取url地址的内容
        $output = curl_exec($ch);
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
            list($responseHeader, $body) = explode("\r\n\r\n", $output, 2);
            $headArr = explode("\r\n", $responseHeader);
            $responseHeaderArr = [];
            foreach ($headArr as $loop) {
                $arr = explode(': ', $loop);
                $responseHeaderArr[$arr[0]] = $arr[1];
            }
            $reponseBody = json_decode($body, true);
            
            return [$responseHeaderArr, $reponseBody];
        }
        
        return ['', ''];
    }
    
}
