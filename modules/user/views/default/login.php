<?php
/**
 * @var $this yii\web\View
 * @var $loginModel \app\modules\user\models\LoginForm
 * @var $signupModel \app\modules\user\models\LoginForm
 */


use app\widgets\EAuthWidget;
use yii\bootstrap\Tabs;

$this->title = 'Вход на сайт';
$this->params['breadcrumbs'][] = $this->title;

?>


    <div class="row centered">

        <div class="col-lg-4 col-lg-offset-4">
            <div class="panel panel-default panel-shadow">

                <div class="panel-body">
                    <h3 class="centered">Войти с помощью:</h3>
                    <?php echo EAuthWidget::widget([
                        'action' => '/user/default/login'
                    ]); ?>
                    <div class="ceneterd">или</div>
                </div>

                <?

                $loginTabActive = true;
                $signupTabActive = false;
                if ( $signupModel->getIsLoad() ) {
                    $loginTabActive = false;
                    $signupTabActive = true;
                }

                ?>

                <?= Tabs::widget([
                    'options' => [
                        'class' => 'nav-center'
                    ],
                    'linkOptions' => ['style' => 'width:140px;'],
                    'itemOptions' => ['class' => 'panel-body'],
                    'items' => [
                        [
                            'label' => 'Вход',
                            'content' => $this->render('_login', ['model' => $loginModel]),
                            'active' => $loginTabActive,
                        ],
                        [
                            'label' => 'Регистрация',
                            'content' => $this->render('_signup', ['model' => $signupModel]),
                            'active' => $signupTabActive,
                        ]
                    ]
                ]);?>
            </div>
        </div>
    </div>