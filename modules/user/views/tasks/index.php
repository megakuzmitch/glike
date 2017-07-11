<?php
/**
 * @var \yii\web\View $this
 * @var $content
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $taskFilter \app\modules\user\models\TaskFilter
 */
use app\modules\user\models\Task;
use yii\bootstrap\ActiveForm;
use yii\helpers\Json;

?>

<div class="user-tasks">

    <div class="filter">
        <? $filterForm = ActiveForm::begin([
            'method' => 'get',
            'fieldClass' => 'app\widgets\ActiveField',
            'enableClientValidation' => false,
            'enableAjaxValidation' => false,
            'options' => [
                'class' => 'task-filter-form',
                'data-list' => 'TaskList',
                'data-model-name' => 'TaskFilter'
            ]
        ]) ?>

        <?= $filterForm->field($taskFilter, 'service_type')
            ->label(false)
            ->inline()
            ->radioList(Task::getServiceTypeNames(), [
                'data-service-type-associations' => Json::encode(Task::getServiceTypeAssociations())
            ]) ?>

        <?= $filterForm->field($taskFilter, 'task_type')
            ->label(false)
            ->inline()
            ->radioList(Task::getTaskTypes(), ['class' => 'custom-radio-list-sm']) ?>

        <? $filterForm->end() ?>
    </div>

    <?= $this->render('_list', ['dataProvider' => $dataProvider, 'taskFilter' => $taskFilter]) ?>

</div>
