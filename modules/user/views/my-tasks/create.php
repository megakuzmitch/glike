<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 01.06.17
 * Time: 14:24
 *
 * @var $model TaskForm
 */

use app\modules\user\models\TaskForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<div class="user-task-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Подсказака для добавления задания</p>

    <div class="row">
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin(['id' => 'user-task-create-form',
                'layout' => 'horizontal',
            ]); ?>

            <?= $form->errorSummary($model); ?>

            <?= $form->field($model, 'service_type')->radioList(TaskForm::getServiceTypes()) ?>
            <?= $form->field($model, 'task_type')->radioList(TaskForm::getTaskTypes()) ?>
            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>
            <?//= $form->field($model, 'description')->textInput() ?>
            <?= $form->field($model, 'link')->textInput() ?>
            <?= $form->field($model, 'need_count')->textInput() ?>
            <?= $form->field($model, 'points')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Создать задание', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
