<?php

use yii\helpers\ArrayHelper;

$params = ArrayHelper::merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$eauth = ArrayHelper::merge(
    require(__DIR__ . '/eauth.php'),
    require(__DIR__ . '/eauth-local.php')
);

return [
    'name' => 'GLike',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'bootstrap' => ['log'],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8',
        ],
        'eauth' => $eauth,
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'app\auth\Youtube',
                    'clientId' => 'google_client_id',
                    'clientSecret' => 'google_client_secret',
                    'normalizeUserAttributeMap' => [
//                        'displayName' => 'displayName',
                        'imageUrl' => function($attributes) {
                            return $attributes['image']['url'];
                        }
                    ]
                ],
                'instagram' => [
                    'class' => 'app\auth\Instagram',
                    'clientId' => 'instagramm_client_id',
                    'clientSecret' => 'instagramm_client_secret',
                    'normalizeUserAttributeMap' => [
                        'displayName' => 'full_name',
                        'imageUrl' => 'profile_picture'
                    ]
                ]
            ],
        ],
        'i18n' => [
            'translations' => [
                'eauth' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@eauth/messages',
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '' => 'main/default/index',
                '<_a:error>' => 'main/default/<_a>',

                'page/<pageName:about|help>' => 'main/page/view',

//                '<_a:auth>/<authclient:[A-Za-z_-]>'=> 'user/security/<_a>',

//                '<_a:(auth)>/<service:vkontakte|google>' => 'user/social/<_a>',

//                '<_a:(login)>/<service:vkontakte|google>' => 'user/default/<_a>',
//                '<_a:(login|logout|signup|email-confirm|password-reset-request|password-reset)>' => 'user/default/<_a>',

                '<_m:[\w\-]+>/<_c:[\w\-]+>' => '<_m>/<_c>/index',
                '<_m:[\w\-]+>' => '<_m>/default/index',
                '<_m:[\w\-]+>/<_c:[\w\-]+>/<id:\d+>' => '<_m>/<_c>/view',
                '<_m:[\w\-]+>/<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_m>/<_c>/<_a>',
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
        'cache' => [
            'class' => 'yii\caching\DummyCache',
        ],
        'log' => [
            'class' => 'yii\log\Dispatcher',
        ],
    ],
    'modules' => [
        'main' => [
            'class' => 'app\modules\main\Module',
        ],
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableUnconfirmedLogin' => true,
            'modelMap' => [
                'User' => 'app\models\user\User',
                'RegistrationForm' => 'app\models\user\RegistrationForm'
            ],
            'controllerMap' => [
                'security' => 'app\controllers\user\SecurityController'
            ]
        ],
        'task' => [
            'class' => 'app\modules\task\Module'
        ]
    ],
    'params' => $params,
];
