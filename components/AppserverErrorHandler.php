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
     * @return | mix ,如果开启了 YII_DEBUG ，那么就会输出json格式的报错信息，如果没有开启，则返回模糊的报错信息
     */
    public function renderException($exception)
    {
        // 组装异常数据
        $exceptionInfo = [
            'code'    => $exception->statusCode ?: 500,
            'message' => $exception->getMessage(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
            'time'    => date('Y-m-d H:i:s', time()),
            'ip'      => Yii::$app->request->userIP,
        ];
        $errorContent = json_encode($exceptionInfo, JSON_UNESCAPED_UNICODE);
        // 如果是线上环境，代码错误，可以在下面，通过发邮件，或者用错误日志收集工具等收集错误信息。
        if (YII_ENV == 'prod') {
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
        if (YII_DEBUG !== true) { // 对于线上，银行报错的具体细细
            $exceptionInfo = [
                'code'    => $exception->statusCode ?: 500,
                'message' => 'system error',
                'time'    => date('Y-m-d H:i:s', time()),
                'ip'      => Yii::$app->request->userIP,
            ];
            $errorContent = json_encode($exceptionInfo, JSON_UNESCAPED_UNICODE);
        }
        exit($errorContent);
    }
}
