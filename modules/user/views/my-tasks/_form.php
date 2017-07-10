<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 14.06.17
 * Time: 10:33
 *
 *
 * @var $model TaskForm
 * @var $formAction
 */

use app\modules\user\models\Task;
use app\modules\user\models\TaskForm;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Json;

$formParams = [
    'id' => 'user-task-form',
    'fieldClass' => 'app\widgets\ActiveField',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validationUrl' => ['/user/my-tasks/validate', 'id' => $model->getId()],
];

if ( isset($formAction) ) {
    $formParams['action'] = $formAction;
}

?>

<?php $form = ActiveForm::begin($formParams); ?>

<?= $form->field($model, 'service_type')
    ->label(false)
    ->radioList(Task::getServiceTypeLabels(), [
        'class' => 'centered',
        'disabled' => !$model->isNew,
        'data-service-type-associations' => Json::encode(Task::getServiceTypeAssociations())
    ]) ?>

<?
    $taskTypeOptions = [
        'disabled' => !$model->isNew
    ];
?>
<?= $form->field($model, 'task_type')
    ->label(false)
    ->inline()
    ->radioList(Task::getTaskTypes(), [
        'class' => 'custom-radio-list-sm',
        'item' => function($index, $label, $name, $checked, $value) use ($taskTypeOptions, $model) {

            $availableTaskTypes = array_keys(Task::getTaskTypes($model->service_type));
            $wrapperOptions = ['class' => 'custom-radio inline'];
            if ( !in_array($value, $availableTaskTypes) ) {
                $wrapperOptions['style'] = 'display:none;';
            }

            $id = Html::getInputId($model, 'task_type') . '_' . $index;
            $inputOptions = ['id' => $id, 'checked' => $checked];
            if ( array_key_exists('disabled', $taskTypeOptions) && $taskTypeOptions['disabled'] ) {
                $inputOptions['disabled'] = true;
            }
            return Html::tag('div', Html::input('radio', $name, $value, $inputOptions) .
                Html::label($label, $id), $wrapperOptions);
        }
    ]) ?>

<?= $form->field($model, 'link')->textInput(['disabled' => !$model->isNew]) ?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'need_count')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'points')->textInput() ?>
    </div>
</div>


<div class="form-group centered">
    <? if ( Yii::$app->request->isAjax ) {
        echo Html::a('Отмена', '', ['class' => 'btn btn-default', 'data-dismiss' => 'modal', 'aria-hidden' => true]);
    }?>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
