<?php
/**
 * @var \yii\web\View $this
 * @var $content
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $taskFilter \app\modules\user\models\TaskFilter
 */
use app\modules\user\models\Task;
use app\widgets\EAuthWidget;
use yii\widgets\ListView;

if ( $taskFilter->service_type ) {
    $service = Yii::$app->eauth->getIdentity(Task::getServiceType($taskFilter->service_type));
} else {
    $service = null;
}

?>

<div class="user-tasks list-view-container">

    <? if ( $service && !$service->getIsAuthenticated() ): ?>
        <div class="row centered">' .
            <div class="col-lg-4">
            <?= EAuthWidget::widget([
                'action' => '/user/social/auth',
                'predefinedServices' => [$service->getServiceName()]
            ]); ?>
            </div>
        </div>

    <? else: ?>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_list_item',
            'options' => [
                'id' => 'TaskList',
                'class' => 'task-list row',
            ],
            'layout' => '{items}',
        ]) ?>
    <? endif ?>

</div>