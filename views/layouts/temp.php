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


<body>

<div class="navmenu navmenu-fixed-left">

    <div class="navmenu-header"></div>

    <!--    <div class="account-block">-->
    <!---->
    <!--        <div class="avatar">-->
    <!--            <img src="/img/pic4.jpg" alt="" class="img-thumbnail">-->
    <!--        </div>-->
    <!---->
    <!--        <div class="info">-->
    <!--            <div class="name">My name</div>-->
    <!--            <div class="points">65 баллов</div>-->
    <!--        </div>-->
    <!---->
    <!--        <a href="#" class="btn btn-danger btn-sm">Настройки профиля</a>-->
    <!---->
    <!--    </div>-->

    <ul class="nav navmenu-nav">
        <li role="presentation" class="active">
            <a href="#"><i class="fa fa-user-circle-o"></i>Моя страница</a>
        </li>

        <li role="presentation">
            <a href="<?= Url::to(['/user/tasks/index']) ?>"><i class="fa fa-diamond"></i>Заработать</a>
        </li>

        <li role="presentation">
            <a href="<?= Url::to(['/user/my-tasks/index']) ?>"><i class="fa fa-flag"></i>Мои задания</a>
        </li>
    </ul>
</div>

<div class="canvas">

    <div class="container-fluid">
        <div class="page-header">
            <h1>Off Canvas Reveal Menu Template</h1>
        </div>
        <p class="lead">This example demonstrates the use of the offcanvas plugin with a reveal effect.</p>
        <p>On the contrary of the push effect, the menu doesn't move with the canvas.</p>
        <p>You get the reveal effect by wrapping the content in a div and setting the <code>canvas</code> option to target that div.</p>
        <p>Note that in this example, the navmenu doesn't have the <code>offcanvas</code> class, but is placed under the canvas by setting the <code>z-index</code>.</p>
        <p>Also take a look at the examples for a navmenu with <a href="../navmenu">slide in effect</a> and <a href="../navmenu-push">push effect</a>.</p>
    </div><!-- /.container -->

</div>



<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
