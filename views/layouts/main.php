<?php

/* @var $this \yii\web\View */
/* @var $content string */


use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\helpers\Url;
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

<div class="navbar navbar-inverse">

    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?= Url::home() ?>">
                <?= Html::img('/img/Logo-G.png', [
                    'alt' => 'GLike'
                ]) ?><span>Like</span>
            </a>
        </div>
        <?php Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => array_filter([
                ['label' => 'Главная', 'url' => ['/main/default/index']],
                ['label' => 'Профиль', 'url' => ['/user/tasks/index']],
            ]),
        ]); ?>
    </div>

</div>

<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
