<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 11.05.2017
 * Time: 16:41
 *
 * @var \yii\web\View $this
 * @var $content
 */

use yii\bootstrap\Nav;
use yii\helpers\Url;

?>


<? $this->beginContent('@app/views/layouts/_body.php') ?>


<div class="navmenu navmenu-fixed-left user-sidebar">

    <div class="toggle-wrap"><span class="toggle"><i class="fa fa-chevron-right"></i></span></div>

    <?= Nav::widget([
        'options' => [
            'class' => 'nav navmenu-nav'
        ],
        'encodeLabels' => false,
        'items' => [
            ['label' => "<i class='fa fa-user-circle-o'></i><span>Моя страница</span>", 'url' => ['#']],
            ['label' => "<i class='fa fa-diamond'></i><span>Заработать</span>", 'url' => ['/user/tasks/index']],
            ['label' => "<i class='fa fa-flag'></i><span>Мои задания</span>", 'url' => ['/user/my-tasks/index']],
        ]
    ]) ?>
</div>


<div class="canvas">

    <? $this->beginContent('@app/views/layouts/main_fluid.php') ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="well">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div><!-- /.container -->

    <? $this->endContent() ?>
</div>

<? $this->endContent() ?>


