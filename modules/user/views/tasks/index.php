<?php
/**
 * @var \yii\web\View $this
 * @var $content
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

?>

<div class="user-tasks">

    <h1><?= \yii\helpers\Html::encode($this->title) ?></h1>


    <ul class="nav nav-pills" role="tablist">
        <li role="presentation" class="active"><a href="#vk-tab" aria-controls="vk-tab" role="tab" data-toggle="tab">В контакте</a></li>
        <li role="presentation"><a href="#youtube-tab" aria-controls="youtube-tab" role="tab" data-toggle="tab">Youtube</a></li>
        <li role="presentation"><a href="#instagramm-tab" aria-controls="instagramm-tab" role="tab" data-toggle="tab">Instagramm</a></li>
        <li role="presentation"><a href="#twitter-tab" aria-controls="twitter-tab" role="tab" data-toggle="tab">Twitter</a></li>
        <li role="presentation"><a href="#ok-tab" aria-controls="ok-tab" role="tab" data-toggle="tab">Одноклассники</a></li>
    </ul>



    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="vk-tab">

            <div class="filter">
                <ul class="nav nav-pills nav-pills-sm">
                    <li role="presentation" class="active"><a href="#">Поставить лайк</a></li>
                    <li role="presentation" class=""><a href="#">Вступить в группу</a></li>
                    <li role="presentation" class=""><a href="#">Добавить в друзья</a></li>
                    <li role="presentation" class=""><a href="#">Сделать репост</a></li>
                </ul>
            </div>

            <?= \yii\widgets\ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_list',
                'options' => [
                    'class' => 'task-list row',
                ],
                'layout' => '{items}'
            ]) ?>

        </div>
        <div role="tabpanel" class="tab-pane" id="youtube-tab">...</div>
        <div role="tabpanel" class="tab-pane" id="instagramm-tab">...</div>
        <div role="tabpanel" class="tab-pane" id="twitter-tab">...</div>
        <div role="tabpanel" class="tab-pane" id="ok-tab">...</div>
    </div>

</div>
