<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

?>
<?php $this->beginBody() ?>


<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-6">
                <div class="navbar-header">
                    <a class="navbar-brand" href="<?= Url::home() ?>">
                        <?= Html::img('/img/Logo-G.png', [
                            'alt' => 'GLike'
                        ]) ?><span>Like</span>
                    </a>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="profile-icon dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false"><img src="/img/pic4.jpg" alt="" class="img-circle"> <span class="caret"></span></a>

                    <div class="dropdown-menu">
                        <div class="account-block">
                            <div class="info">
                                <div class="name">My name</div>
                                <div class="points">65 баллов</div>
                            </div>
                            <a href="#" class="btn btn-danger btn-sm">Настройки профиля</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $content ?>

