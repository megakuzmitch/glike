<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 11.05.2017
 * Time: 16:41
 */
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var $content
 */
?>


<? $this->beginContent('@app/views/layouts/main.php') ?>

        <div class="container">

            <div class="row">

                <div class="col-lg-3">
                    <nav class="user-sidebar" role="navigation">

                        <div class="account-block">

                            <div class="avatar">
                                <img src="/img/pic4.jpg" alt="" class="img-thumbnail">
                            </div>

                            <div class="info">
                                <div class="name">My name</div>
                                <div class="points">65 баллов</div>
                            </div>

                            <a href="#" class="btn btn-danger btn-sm">Настройки профиля</a>

                        </div>

                        <ul class="nav">
                            <li role="presentation">
                                <a href="#"><i class="fa fa-user-circle-o"></i>Моя страница</a>
                            </li>

                            <li role="presentation">
                                <a href="<?= Url::to(['/user/tasks/index']) ?>"><i class="fa fa-diamond"></i>Заработать</a>
                            </li>

                            <li role="presentation">
                                <a href="<?= Url::to(['/user/my-tasks/index']) ?>"><i class="fa fa-flag"></i>Мои задания</a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <div class="col-lg-9">
                    <?= $content ?>
                </div>

            </div>

        </div>

<? $this->endContent() ?>


