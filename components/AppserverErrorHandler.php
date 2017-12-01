<?php
namespace fecshop\components;

use Yii;
use yii\base\ErrorHandler;
use yii\base\Exception;

/**
 * 异常捕获器
 */
class AppserverErrorHandler extends ErrorHandler
{
    /**
     * [renderException description]
     * @property  $exception | Object 异常数据对象
     * 
     */
    public function renderException($exception)
    {
        // 获取异常数据
        $code    = $exception->statusCode ?: 500;  
        $message = $exception->getMessage();
        $name    = $exception->getName();
        $file    = $exception->getFile();
        $line    = $exception->getLine();
        $traceString    = $exception->getTraceAsString();
        $time    = time();
        $ip      = Yii::$app->request->userIP;
        $url     = Yii::$service->url->getCurrentUrl();
        
        $reponse = Yii::$app->response;
        Yii::$app->response->format = $reponse::FORMAT_JSON;
        if (YII_ENV == 'prod' || YII_DEBUG == false) {
            $errorKey = $this->saveProdException($code, $message, $file, $line, $time, $ip, $name, $traceString, $url);
            Yii::$app->response->data = [
                'code'      => $code,
                'error_no'  => $errorKey,
            ];
            Yii::$app->response->send();
            Yii::$app->end();
        } else {
            $time    = date('Y-m-d H:i:s', $time);
            $exceptionInfo = [
                'code'      => $code, 
                'message'   => $message,
                'file'      => $file,
                'line'      => $line,
                'time'      => $time,
                'ip'        => $ip,
                'name'      => $name,
                'traceString' => $traceString,
            ];
            Yii::$app->response->data = $exceptionInfo;
            Yii::$app->response->send();
            Yii::$app->end();
        }
    }
        
    public function saveProdException($code, $message, $file, $line, $created_at, $ip, $name, $trace_string, $url){
        return Yii::$service->helper->errorHandler->saveByErrorHandler(
            $code, $message, $file, $line, $created_at,
            $ip, $name, $trace_string, $url
        );
        
    }
    /**
     * 这块代码目前没有编写，您也可以用 Sentry（错误日志收集框架） 来收集错误日志。
     * $option = [
     *   'fromMail' => 'xxx@xxx.com',
     *   'subject'  => 'fecshop报错 Code:' . $exceptionInfo['code'],
     *   'htmlBody' => '异常' . '(' . YII_ENV . ')' . $exceptionInfo['code'] . ':' . $exceptionInfo['message'] . '<br />文件：' . $exceptionInfo['file'] . ':' . $exceptionInfo['line'] . '<br /> 时间：' . $exceptionInfo['time'] . '<br />请求ip：' . $exceptionInfo['ip'],
     * ];
     *
     *
     *if (!$result) {
     *    throw new Exception("Exception mail send faild!", 1);
     *}
     */
}
