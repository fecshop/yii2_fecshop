<?php
namespace fecshop\app\console\modules\Amqp\block;

use yii\base\BaseObject;

class PushTest extends BaseObject implements \zhuravljov\yii\queue\Job
{
    public $name;
    public $age;
    
    public function execute($queue)
    {
       // \Yii::info('444444','fecshop_debug');
        $d = 'name:'.$this->name.'####'.'age:'.$this->age;
        var_dump($d);  # 输出
    }
}