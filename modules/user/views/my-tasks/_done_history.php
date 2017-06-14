<?php

/**
 * @var $model app\modules\user\models\Task
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use yii\grid\GridView;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'followers.name',
    ]

]);
