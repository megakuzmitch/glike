<?php
/**
 *
 */
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\helpers\Url;

?>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">

        <div class="navbar-header">
            <a class="navbar-brand" href="<?= Url::home() ?>">
                <?= Html::img('/img/Logo-G.png', [
                    'alt' => 'GLike'
                ]) ?><span>Like</span>
            </a>
        </div>

        <?php echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => array_filter([
                ['label' => 'Помощь', 'url' => ['/main/page/help']],
                ['label' => 'О нас', 'url' => ['/main/page/about']]
            ]),
        ]); ?>

    </div>
</div>
