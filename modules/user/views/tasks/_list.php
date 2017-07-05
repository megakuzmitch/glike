<?php
/**
 * @var \yii\web\View $this
 * @var $content
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

?>

<div class="user-tasks">

    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_list_item',
        'options' => [
            'id' => 'TaskList',
            'class' => 'task-list row',
        ],
        'layout' => '{items}'
    ]) ?>

</div>