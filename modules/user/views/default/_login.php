<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 12.07.17
 * Time: 16:35
 *
 * @var $model \app\modules\user\models\LoginForm
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>


<div class="user-default-login">
    <? $form = ActiveForm::begin(['id' => 'form-login', 'options' => ['class' => 'form-without-spacing']]); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'email')
        ->label(false)
        ->textInput([
            'placeholder' => $model->getAttributeLabel('email'),
            'class' => 'form-control input-lg'
        ]) ?>
    <?= $form->field($model, 'password')
        ->label(false)
        ->passwordInput([
            'placeholder' => $model->getAttributeLabel('password'),
            'class' => 'form-control input-lg'
        ]) ?>

    <?= Html::submitButton('Войти', ['class' => 'btn btn-lg btn-primary', 'name' => 'login-button']) ?>

    <? ActiveForm::end(); ?>
</div>
