<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 02.06.17
 * Time: 15:35
 */

namespace app\assets;


use yii\web\AssetBundle;

class FancyboxAsset extends AssetBundle
{
    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.css'
    ];

    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.js',
    ];
}