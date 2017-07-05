<?php

/* @var $this \yii\web\View */
use app\widgets\Alert;

/* @var $content string */
?>

<?php $this->beginContent('@app/views/layouts/_body.php') ?>

<div class="navbar navbar-inverse">
    <div class="container">

        <?= $this->render('_navbar') ?>

    </div>
</div>

<div class="main-simple">

    <div class="container">
        <h1><?= $this->title; ?></h1>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>

</div>

<?php $this->endContent() ?>
