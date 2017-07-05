<?php
/**
 * @var $this yii\web\View
 * @var $model \app\modules\user\models\LoginForm
 */


use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Вход на сайт';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-default-login">

    <div class="row centered">

        <div class="col-lg-4 col-lg-offset-4">
<!--            --><?php //echo \nodge\eauth\Widget::widget([
//                'action' => Yii::$app->user->loginUrl
//            ]); ?>

            <? $form = ActiveForm::begin(['id' => 'form-login']); ?>

                <?= $form->field($model, 'email')->textInput() ?>
                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    <div> или <?= Html::a('зарегестрироваться', ['/user/default/signup']) ?></div>
                </div>

            <? ActiveForm::end(); ?>

        </div>

    </div>



</div>