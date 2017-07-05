<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\user\models\SignupForm */

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-signup">

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'form-signup',
                'enableAjaxValidation' => true,
                'enableClientValidation' => false
            ]); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'role' => 'email']) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Зарегестрироваться и войти', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                <div> или <?= Html::a('вход', ['/user/default/login']) ?></div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>