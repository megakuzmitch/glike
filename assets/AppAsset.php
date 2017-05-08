<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
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
        'fonts/lato/lato.css',
        'fonts/raleway/raleway.css',
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        IESupportAsset::class,
        \rmrevin\yii\fontawesome\AssetBundle::class,
        YiiAsset::class,
        BootstrapAsset::class,
    ];
}
