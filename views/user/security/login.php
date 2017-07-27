<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use app\models\user\RegistrationForm;
use app\widgets\EAuthWidget;
use app\widgets\user\Connect;
use dektrium\user\models\LoginForm;
use yii\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var LoginForm $loginModel
 * @var RegistrationForm $registrationModel
 * @var dektrium\user\Module $module
 */

$this->title = Yii::t('user', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
            <div class="panel panel-default panel-shadow">

                <div class="panel-body centered">
                    <h3>Войти с помощью:</h3>
                    <?= Connect::widget([
                        'baseAuthUrl' => ['/user/security/auth'],
                    ]) ?>
                    <div>или</div>
                </div>

                <div class="panel-body centered">

                    <?= Tabs::widget([
                        'options' => [
                            'class' => 'nav-center'
                        ],
                        'linkOptions' => ['style' => 'width:140px;'],
                        'itemOptions' => ['class' => 'panel-body'],
                        'items' => [
                            [
                                'label' => 'Вход',
                                'content' => $this->render('_login', ['model' => $loginModel, 'module' => $module]),
                                'active' => $loginTabActive,
                            ],
                            [
                                'label' => 'Регистрация',
                                'content' => $this->render('_register', ['model' => $registrationModel, 'module' => $module]),
                                'active' => !$loginTabActive,
                            ]
                        ]
                    ]);?>

                </div>
            </div>
        </div>
    </div>
</div>
