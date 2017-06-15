<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 14.06.17
 * Time: 10:33
 *
 *
 * @var $model TaskForm
 */

use app\modules\user\models\TaskForm;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>

<?php $form = ActiveForm::begin(['id' => 'user-task-form',
    'layout' => 'horizontal',
]); ?>

<?= $form->errorSummary($model); ?>

<?= $form->field($model, 'service_type')->radioList(TaskForm::getServiceTypes(), ['itemOptions' => ['readonly' => !$model->isNew]]) ?>
<?= $form->field($model, 'task_type')->radioList(TaskForm::getTaskTypes(), ['itemOptions' => ['readonly' => !$model->isNew]]) ?>
<?= $form->field($model, 'link')->textInput(['readonly' => !$model->isNew]) ?>
<?= $form->field($model, 'need_count')->textInput() ?>
<?= $form->field($model, 'points')->textInput() ?>

<div class="form-group">
    <div class="col-sm-6 col-sm-offset-3">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
