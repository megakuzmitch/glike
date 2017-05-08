<?php

use yii\helpers\ArrayHelper;

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$config = [
    'id' => 'glike-console',
    'bootstrap' => ['gii'],
    'controllerNamespace' => 'app\commands',
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

return $config;
