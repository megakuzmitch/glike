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
        <div class="row">
            <div class="col-lg-12">

                <div class="navbar-header">

                    <button type="button" class="navbar-toggle sidebar-toggle" data-toggle="canvas" data-target=".user-sidebar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand" href="<?= Url::home() ?>">
                        <?= Html::img('/img/Logo-G.png', [
                            'alt' => 'GLike'
                        ]) ?><span>Like</span>
                    </a>
                </div>

                <?php /* echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => array_filter([
                        ['label' => 'Помощь', 'url' => ['/main/default/help']],
                        ['label' => 'О нас', 'url' => ['/main/default/about']]
                    ]),
                ]); */ ?>
            </div>


        </div>

    </div>
</div>

<?= $content ?>

