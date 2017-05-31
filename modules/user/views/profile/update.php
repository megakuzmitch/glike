<?php
/** @var \app\modules\user\models\User $model */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$form = ActiveForm::begin([]); ?>

<?=
$form->field($model, 'email')->input('email', ['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>