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

?>
<?php $this->beginBody() ?>


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

<?= $content ?>

