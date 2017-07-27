<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = Yii::$app->name;
?>
<div class="main-index">

    <div class="main-header header-wrap">
        <div class="container">
            <h1>Накрутка сайтов - это <b>ЛЕГКО</b></h1>
            <h2>Попробуй сам</h2>
            <?= \yii\helpers\Html::a('Войти', Url::to(['/user/security/login']), [
                'class' => 'btn btn-purple btn-lg'
            ]) ?>

            <ul class="social-slider">
                <li><img src="/img/vk.png" alt=""></li>
                <li><img src="/img/yt.png" alt=""></li>
                <li><img src="/img/in.png" alt=""></li>
            </ul>
        </div><!-- container -->
    </div><!-- headerwrap -->


    <!-- PORTFOLIO SECTION -->
    <div class="dark-gray-wrap">
        <div class="container">
            <div class="row centered">

                <div class="col-lg-4 soc-block">
                    <div class="tilt">
                        <a href="#"><i class="fa fa-vk"></i></a>
                    </div>
                    <h4>В контакте</h4>
                    <ul class="list-style-hand">
                        <li>Накрутка лайков</li>
                        <li>Накрутка комментариев</li>
                        <li>Накрутка репостов</li>
                    </ul>
                </div>

                <div class="col-lg-4 soc-block">
                    <div class="tilt">
                        <a href="#"><i class="fa fa-youtube"></i></a>
                    </div>
                    <h4>Youtube</h4>
                    <ul class="list-style-hand">
                        <li>Накрутка лайков</li>
                        <li>Накрутка комментариев</li>
                        <li>Накрутка репостов</li>
                    </ul>
                </div>

                <div class="col-lg-4 soc-block">
                    <div class="tilt">
                        <a href="#"><i class="fa fa-instagram"></i></a>
                    </div>
                    <h4>Instagramm</h4>
                    <ul class="list-style-hand">
                        <li>Накрутка лайков</li>
                        <li>Накрутка комментариев</li>
                        <li>Накрутка репостов</li>
                    </ul>
                </div>

            </div><!-- row -->

            <div class="row">
                <div class="col-lg-4 col-lg-offset-2 soc-block">
                    <div class="tilt">
                        <a href="#"><i class="fa fa-twitter"></i></a>
                    </div>
                    <h4>Twitter</h4>
                    <ul class="list-style-hand">
                        <li>Накрутка лайков</li>
                        <li>Накрутка комментариев</li>
                        <li>Накрутка репостов</li>
                    </ul>
                </div>

                <div class="col-lg-4 soc-block">
                    <div class="tilt">
                        <a href="#"><i class="fa fa-odnoklassniki"></i></a>
                    </div>
                    <h4>Одноклассники</h4>
                    <ul class="list-style-hand">
                        <li>Накрутка лайков</li>
                        <li>Накрутка комментариев</li>
                        <li>Накрутка репостов</li>
                    </ul>
                </div>
            </div>

            <div class="row centered">
                <?//= Url::to(['/user/security/auth', 'authclient' => 'google']) ?>
                <?= \yii\helpers\Html::a('Войти', Url::to(['/user/security/login']), [
                        'class' => 'btn btn-danger btn-lg'
                ]) ?>
            </div>
        </div><!-- container -->
    </div><!-- DG -->


    <!-- FOOTER -->
    <div class="footer-wrap">
        <div class="container">
            <div class="row centered">
                <a href="#"><i class="fa fa-twitter"></i></a><a href="#"><i class="fa fa-facebook"></i></a><a href="#"><i class="fa fa-dribbble"></i></a>

            </div><!-- row -->
        </div><!-- container -->
    </div><!-- Footer -->


    <!-- MODAL FOR CONTACT -->
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">contact us</h4>
                </div>
                <div class="modal-body">
                    <div class="row centered">
                        <p>We are available 24/7, so don't hesitate to contact us.</p>
                        <p>
                            Somestreet Ave, 987<br/>
                            London, UK.<br/>
                            +44 8948-4343<br/>
                            hi@blacktie.co
                        </p>
                        <div id="mapwrap">
                            <iframe height="300" width="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.google.es/maps?t=m&amp;ie=UTF8&amp;ll=52.752693,22.791016&amp;spn=67.34552,156.972656&amp;z=2&amp;output=embed"></iframe>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Save & Go</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>
