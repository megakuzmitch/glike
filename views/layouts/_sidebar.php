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

use yii\helpers\Url;

?>


<? $this->beginContent('@app/views/layouts/_body.php') ?>


<div class="navmenu navmenu-fixed-left user-sidebar">

    <div class="navmenu-header"></div>

<!--    <div class="account-block">-->
<!---->
<!--        <div class="avatar">-->
<!--            <img src="/img/pic4.jpg" alt="" class="img-thumbnail">-->
<!--        </div>-->
<!---->
<!--        <div class="info">-->
<!--            <div class="name">My name</div>-->
<!--            <div class="points">65 баллов</div>-->
<!--        </div>-->
<!---->
<!--        <a href="#" class="btn btn-danger btn-sm">Настройки профиля</a>-->
<!---->
<!--    </div>-->

    <ul class="nav navmenu-nav">
        <li role="presentation" class="active">
            <a href="#"><i class="fa fa-user-circle-o"></i>Моя страница</a>
        </li>

        <li role="presentation">
            <a href="<?//= Url::to(['/user/tasks/index']) ?>"><i class="fa fa-diamond"></i>Заработать</a>
        </li>

        <li role="presentation">
            <a href="<?//= Url::to(['/user/my-tasks/index']) ?>"><i class="fa fa-flag"></i>Мои задания</a>
        </li>
    </ul>
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


