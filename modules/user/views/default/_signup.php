<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 12.07.17
 * Time: 16:58
 *
 * @var $model SignupForm
 */
use app\modules\user\models\SignupForm;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>

<?php $form = ActiveForm::begin([
    'id' => 'form-signup',
    'options' => ['class' => 'form-without-spacing']
]); ?>

<?= $form->errorSummary($model) ?>

<?= $form->field($model, 'email')
    ->label(false)
    ->textInput([
        'autofocus' => true,
        'role' => 'email',
        'placeholder' => $model->getAttributeLabel('email'),
        'class' => 'form-control input-lg'
    ]) ?>

<?= $form->field($model, 'password')
    ->label(false)
    ->passwordInput([
        'placeholder' => $model->getAttributeLabel('password'),
        'class' => 'form-control input-lg'
    ]) ?>

<?= $form->field($model, 'confirm_password')
    ->label(false)
    ->passwordInput([
        'placeholder' => $model->getAttributeLabel('confirm_password'),
        'class' => 'form-control input-lg'
    ]) ?>

<?= Html::submitButton('Зарегестрироваться и войти', ['class' => 'btn btn-lg btn-primary', 'name' => 'signup-button']) ?>

<?php ActiveForm::end(); ?>
