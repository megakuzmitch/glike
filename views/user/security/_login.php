<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 12.07.17
 * Time: 16:35
 *
 * @var $model \app\modules\user\models\LoginForm
 * @var dektrium\user\Module $module
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>


<div class="user-login">
    <?php $form = ActiveForm::begin([
        'id' => 'form-login',
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'validateOnBlur' => false,
        'validateOnType' => false,
        'validateOnChange' => false,
        'options' => ['class' => 'form-without-spacing']
    ]) ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'login', [
            'inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']
        ])
        ->label(false)
        ->textInput([
            'placeholder' => $model->getAttributeLabel('login'),
            'class' => 'form-control input-lg'
        ]) ?>
    <?= $form->field($model, 'password', [
            'inputOptions' => ['class' => 'form-control', 'tabindex' => '2']
        ])
        ->label(false)
        ->passwordInput([
            'placeholder' => $model->getAttributeLabel('password'),
            'class' => 'form-control input-lg'
        ]) ?>

    <?= Html::submitButton('Войти', ['class' => 'btn btn-lg btn-primary', 'name' => 'login-button']) ?>

    <? if ( $module->enablePasswordRecovery ) {
        echo '<br>';
        echo Html::a(Yii::t('user', 'Forgot password?'),
            ['/user/recovery/request'],
            ['tabindex' => '4']
        );
    } ?>

    <? ActiveForm::end(); ?>
</div>

