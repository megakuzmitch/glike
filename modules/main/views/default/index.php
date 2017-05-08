<?php

/* @var $this yii\web\View */

$this->title = Yii::$app->name;
?>
<div class="main-default-index">
</div>

<div id="headerwrap">
    <div class="container">
        <div class="row centered">
            <div class="col-lg-8 col-lg-offset-2">
                <h1>Накрутка сайтов - это <b>ЛЕГКО</b></h1>
                <h2>Попробуй</h2>
            </div>
        </div><!-- row -->
    </div><!-- container -->
</div><!-- headerwrap -->


<!-- PORTFOLIO SECTION -->
<div id="dg">
    <div class="container">
        <div class="row centered">

            <div class="col-lg-4">
                <div class="tilt">
                    <a href="#"><i class="fa fa-vk" style="font-size: 50px;"></i></a>
                </div>
                <h4>В контакте</h4>
                <ul class="list-style-hand">
                    <li>Накрутка лайков</li>
                    <li>Накрутка комментариев</li>
                    <li>Накрутка репостов</li>
                </ul>
            </div>

            <div class="col-lg-4">
                <div class="tilt">
                    <a href="#"><i class="fa fa-youtube" style="font-size: 50px;"></i></a>
                </div>
                <h4>В контакте</h4>
                <ul class="list-style-hand">
                    <li>Накрутка лайков</li>
                    <li>Накрутка комментариев</li>
                    <li>Накрутка репостов</li>
                </ul>
            </div>

            <div class="col-lg-4">
                <div class="tilt">
                    <a href="#"><i class="fa fa-twitter" style="font-size: 50px;"></i></a>
                </div>
                <h4>В контакте</h4>
                <ul class="list-style-hand">
                    <li>Накрутка лайков</li>
                    <li>Накрутка комментариев</li>
                    <li>Накрутка репостов</li>
                </ul>
            </div>

        </div><!-- row -->

        <div class="row centered" style="margin-top: 30px;">
            <a href="/login" class="btn btn-lg btn-danger">Войти</a>
        </div>
    </div><!-- container -->
</div><!-- DG -->


<!-- FOOTER -->
<div id="f">
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
