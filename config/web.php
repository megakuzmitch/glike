<?php

$config = [
    'id' => 'app',
    'language' => 'ru_RU',
    'defaultRoute' => 'main/default/index',
    'on beforeAction' => function ($event) {
        $controller = $event->action->controller;
        if ( Yii::$app->user->isGuest ) {
            $controller->layout = '@app/views/layouts/main';
        } else {
            $controller->layout = '@app/views/layouts/user';
        }
    },
    'components' => [
//        'user' => [
//            'identityClass' => 'app\modules\user\models\User',
//            'enableAutoLogin' => true,
//            'loginUrl' => '/user/default/login',
//        ],
        'request' => [
            'cookieValidationKey' => 'GZK683ggYTKtaIA9Ai0238rhfdshf',
        ],
        'errorHandler' => [
            'errorAction' => 'main/default/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [YII_DEBUG ? 'js/bootstrap.js' : 'js/bootstrap.min.js']
                ]
            ]
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/views/user'
                ],
            ]
        ]
    ]
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
