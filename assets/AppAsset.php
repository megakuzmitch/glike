<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'fonts/lato/lato.css',
        'fonts/raleway/raleway.css',
        'css/animate.min.css',
        'css/main.css',
    ];
    public $js = [
        'js/device.min.js',
        'js/hammer.min.js',
        'js/hammer-time.min.js',
        'js/jquery.hammer.js',
        'js/mobilesidebar.js',
        'js/ajax-modal-popup.js',
        'js/bootbox.min.js',
        'js/reflection.js',
        'js/main.js'
    ];
    public $depends = [
        'app\assets\IESupportAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'app\assets\BootstrapNotifyAsset',
        'app\assets\Cloud9Carousel',
    ];
}
