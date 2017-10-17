<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 08.06.17
 * Time: 15:39
 */

namespace app\assets;


use yii\web\AssetBundle;

class BootstrapNotifyAsset extends AssetBundle
{
    public $sourcePath = '@bower/remarkable-bootstrap-notify/dist';
    public $js = [
        'bootstrap-notify.min.js',
    ];
}