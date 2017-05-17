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


<body>

<div class="navmenu navmenu-default navmenu-fixed-left">
    <a class="navmenu-brand" href="#">Project name</a>
    <ul class="nav navmenu-nav">
        <li><a href="../navmenu/">Slide in</a></li>
        <li><a href="../navmenu-push/">Push</a></li>
        <li class="active"><a href="./">Reveal</a></li>
        <li><a href="../navbar-offcanvas/">Off canvas navbar</a></li>
    </ul>
    <ul class="nav navmenu-nav">
        <li><a href="#">Link</a></li>
        <li><a href="#">Link</a></li>
        <li><a href="#">Link</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
            <ul class="dropdown-menu navmenu-nav">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
            </ul>
        </li>
    </ul>
</div>

<div class="canvas">

    <div class="navbar navbar-inverse navbar-fixed-top">

        <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-recalc="false" data-target=".navmenu" data-canvas=".canvas">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Brand</a>
            </div>

        </div><!-- /.container-fluid -->
    </div>

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
