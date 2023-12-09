<?php

use uims\user\modules\jiuser\models\User;

$params = require(__DIR__ . '/params.php');
$dbParams = require(__DIR__ . '/db.php');
/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'search-engine',
    'basePath' => dirname(__DIR__),
    'layout' => false,
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
            'class' => 'app\components\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
//            'useFileTransport' => false,
        ],
        'db' => $dbParams,
        'user' => [
            'class' => User::class,
            'identityClass' => '\uims\user\modules\jiuser\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['site/login'],
            'as authLog' => [
                'class' => 'yii2tech\authlog\AuthLogWebUserBehavior'
            ],
        ],
        'request' => [
            'class' => 'yii\console\Request',
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
        'search' => [
            'class' => 'uims\searchengine\modules\search\Module',
        ],
        'examination' => [
            'class' => 'uims\examination\modules\examination\Module',
        ],
        'examstudent' => [
            'class' => 'uims\examination\modules\examstudent\Module',
        ],
    ],
    'params' => $params,
];
