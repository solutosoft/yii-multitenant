<?php


return  [
    'class' => 'yii\web\Application',
    'id' => 'yii-multitenant-web-test',
    'basePath' => dirname(__DIR__) . '/../',
    'vendorPath' => __DIR__ . '/../../vendor',
    'timeZone' => 'UTC',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlite::memory:',
        ],
        'user' => [
            'identityClass' => 'Soluto\Multitenant\Tests\Models\Person',
            'authTimeout' => 10,
            'enableSession' => false,
        ],
        'request' => [
            'cookieValidationKey' => 'TPweYCnTCow7EwZlCkjOYsSL',
            'scriptFile' => __DIR__ .'/index.php',
            'scriptUrl' => '/index.php',
        ],
    ]
];
