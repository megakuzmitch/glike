<?php

/* @var $this \yii\web\View */
use app\widgets\Alert;

/* @var $content string */
?>

<? $this->beginContent('@app/views/layouts/_sidebar.php') ?>

    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">

            <?= $this->render('_navbar') ?>

        </div>
    </div>

    <div class="container-fluid">
        <?= $content ?>
    </div><!-- /.container -->

<?php $this->endContent() ?>
