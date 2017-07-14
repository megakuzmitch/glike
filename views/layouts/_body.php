<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\EAuthWidget;
use yii\bootstrap\Modal;
use yii\helpers\Html;
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

<?= $content ?>

    <? Modal::begin([
        'header' => '<h4 class="modal-title">Привязка к социальным сетям</h4>',
        'headerOptions' => ['id' => 'modal-header'],
        'id' => 'modal-auth',
        //keeps from closing modal with esc key or by clicking out of the modal.
        // user must click cancel or X to close
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
    ]) ?>
        <div id='modal-content'>
            <?= EAuthWidget::widget([
                'action' => '/user/social/auth'
            ]); ?>
        </div>
    <? Modal::end() ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
