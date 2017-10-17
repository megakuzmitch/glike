<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 28.07.17
 * Time: 12:21
 */

namespace app\assets;


use yii\web\AssetBundle;

class Cloud9Carousel extends AssetBundle
{
    public $sourcePath = '@bower/cloud9carousel';
    public $js = [
        'jquery.cloud9carousel.js',
    ];
}