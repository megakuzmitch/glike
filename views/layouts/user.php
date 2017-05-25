<?php

/* @var $this \yii\web\View */
use yii\bootstrap\Nav;

/* @var $content string */
?>

<? $this->beginContent('@app/views/layouts/_sidebar.php') ?>

    <?= $this->render('_navbar_fluid') ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <?= $content ?>
            </div>
        </div>
    </div><!-- /.container -->

<?php $this->endContent() ?>
