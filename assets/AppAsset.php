<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'vendor/jasny-bootstrap/jasny-bootstrap.min.css',
        'fonts/lato/lato.css',
        'fonts/raleway/raleway.css',
        'css/main.css',
    ];
    public $js = [
        'js/jquery.mobile.custom.min.js',
        'js/mobilesidebar.js',
        'js/main.js',
//        'vendor/jasny-bootstrap/jasny-bootstrap.min.js',
    ];
    public $depends = [
        BootstrapAsset::class,
        IESupportAsset::class,
        \rmrevin\yii\fontawesome\AssetBundle::class,
        YiiAsset::class
    ];
}
