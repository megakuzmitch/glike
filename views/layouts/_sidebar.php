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

?>

<?php $this->beginContent('@app/views/layouts/_body.php') ?>

<div class="navmenu navmenu-fixed-left user-sidebar full">

    <button type="button" class="navbar-toggle sidebar-toggle" data-toggle="canvas" data-target=".user-sidebar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>

    <ul class="nav navmenu-nav">
        <li class="account">
            <a href="#"><i class='fa fa-user-circle-o'></i><img src="/img/pic4.jpg" alt="Img" class="img-circle"></a>
            <div class="info">
                <div class="name">My name</div>
                <div class="points">65 баллов</div>
                <a href="#" class="btn btn-sm btn-danger">Пополнить</a>
            </div>
        </li>
    </ul>

    <?= Nav::widget([
        'options' => [
            'class' => 'nav navmenu-nav'
        ],
        'encodeLabels' => false,
        'items' => [
            ['label' => "<i class='fa fa-diamond'></i><span>Заработать</span>", 'url' => ['/user/tasks/index']],
            ['label' => "<i class='fa fa-flag'></i><span>Мои задания</span>", 'url' => ['/user/my-tasks/index']],
            ['label' => "<i class='fa fa-handshake-o'></i><span>Партнерка</span>", 'url' => ['/user/referal/view']],
            ['label' => "<i class='fa fa-sign-out'></i><span>Выход</span>", 'url' => ['/user/default/logout']]
        ]
    ]) ?>

</div>

<div class="canvas">
    <?= $content; ?>
</div>

<? $this->endContent() ?>
