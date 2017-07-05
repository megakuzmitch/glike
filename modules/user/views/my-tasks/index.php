<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 11.05.2017
 * Time: 20:43
 *
 * @var \yii\web\View $this
 * @var $content
 * @var $taskFilter TaskFilter
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use app\modules\user\models\Task;
use app\modules\user\models\TaskFilter;
use yii\bootstrap\ActiveForm;
use yii\helpers\Json;
use yii\helpers\Url;

?>


<div class="user-my-tasks">

    <div class="row">
        <div class="col-lg-12">
            <div class="tools">
                <?= \yii\bootstrap\Html::a('Добавить задание', ['/user/my-tasks/create'], [
                    'class' => 'btn btn-success show-modal-btn'
                ]) ?>
            </div>
        </div>
    </div>

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

    <?= $this->render('_list', [
        'taskFilter' => $taskFilter,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
