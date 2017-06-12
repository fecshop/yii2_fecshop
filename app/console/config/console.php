<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php
$modules = [];
foreach (glob(__DIR__ . '/modules/*.php') as $filename) {
    $modules = array_merge($modules, require($filename));
}

return [
    'modules'=>$modules,
    'params' => [
        'appName' => 'console',
    ],
    
    //配置RabbitMq 部分
    /*
    'bootstrap' => [
        'queue', // The component registers own console commands
    ],
    
    'components' => [
        'queue' => [
            //'class' => \zhuravljov\yii\queue\amqp\Queue::class,
            //'class' => 'zhuravljov\yii\queue\amqp\Queue',
            'class' => 'fecshop\app\console\modules\Amqp\block\Queue',
            'host'  => 'localhost',
            'port'  => 5672,
            'user'  => 'mqadmin',
            'password' => 'mqadmin20177',
            //'queueName' => 'queue',
            'queueName' => 'productDropshipQN',
            'exchangeName' => 'productDropshipEX',
            'routingKey' => 'productDropshipRT',
            
        ],
    ],
    */
    
];
