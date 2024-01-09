<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'timeZone' => 'Asia/Tashkent',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'telegram' => [
            'class' => 'aki\telegram\Telegram',
            'botToken' => '1611335763:AAF6-m9wtcdiYUiVi8pefTrGdnTeYuOYjvk',
        ],
        'telegram2' => [
            'class' => 'aki\telegram\Telegram',
            'botToken' => '1641554982:AAGuwFCkHrQ93qPcS6IrQPgbmuW5mH74lh8',
        ],
        'telegram3' => [
            'class' => 'aki\telegram\Telegram',
            'botToken' => '1576388332:AAG2d9KptV4wICUw6BMujGc3aYEvo1wzQNs',
        ],
        'telegram4' => [
            'class' => 'aki\telegram\Telegram',
            'botToken' => '1659367153:AAG9gN37fDiIbj9zvD5ZZzpfKG-p_vnX6Uk',
        ],
        'telegram5' => [
            'class' => 'aki\telegram\Telegram',
            'botToken' => '1507522748:AAEEk43wU0wjQCAKkN6tOVmY0SDdob5Ggh0',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ewfasdfsdf',
        ],
        'view' => [
            'theme' => [
            'pathMap' => [
            '@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app'
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableSession' => true,
            'authTimeout' => 60*30,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // '/index.php/orders/index' => 'orders/index',
            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
