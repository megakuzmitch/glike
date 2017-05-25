<?php

/* @var $this \yii\web\View */
/* @var $content string */
?>

<?php $this->beginContent('@app/views/layouts/_body.php') ?>

<?= $this->render('_navbar') ?>

<div class="page">

    <div class="container">
        <?= $content ?>
    </div>

</div>

<?php $this->endContent() ?>
