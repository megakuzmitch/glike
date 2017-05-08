<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!-- Fixed navbar -->
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">GLIKE.C<i class="fa fa-circle"></i>M</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="/">ГЛАВНАЯ</a></li>
                <li>
                    <a data-toggle="modal" data-target="#modal-login" href="#modal-login">ВХОД</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>

<?= $content ?>

<!--<div class="wrap">
    <?php
//    NavBar::begin([
//        'brandLabel' => Yii::$app->name,
//        'brandUrl' => Yii::$app->homeUrl,
//        'options' => [
//            'class' => 'navbar-inverse navbar-fixed-top',
//        ],
//    ]);
//    echo Nav::widget([
//        'options' => ['class' => 'navbar-nav navbar-right'],
//        'items' => array_filter([
//            ['label' => 'Главная', 'url' => ['/main/default/index']],
//            ['label' => 'Обратная связь', 'url' => ['/main/contact/index']],
//            Yii::$app->user->isGuest ?
//                ['label' => 'Регистрация', 'url' => ['/user/default/signup']] :
//                false,
//            !Yii::$app->user->isGuest ?
//                ['label' => 'Профиль', 'url' => ['/user/profile/index']] :
//                false,
//            Yii::$app->user->isGuest ?
//                ['label' => 'Вход', 'url' => ['/user/default/login']] :
//                ['label' => 'Выход (' . Yii::$app->user->identity->username . ')',
//                    'url' => ['/user/default/logout'],
//                    'linkOptions' => ['data-method' => 'post']],
//        ]),
//    ]);
//    NavBar::end();
    ?>

    <div class="container">
        <?//= Breadcrumbs::widget([
          //  'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        //]) ?>
        <?// Alert::widget() ?>
        <?//= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?//= Yii::$app->name ?> <?= date('Y') ?></p>

        <p class="pull-right"><?//= Yii::powered() ?></p>
    </div>
</footer>-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
