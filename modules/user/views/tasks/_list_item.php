<?php
/**
 * @var $model \app\modules\user\models\Task
 */
use app\helpers\TaskHelper;

?>


<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
    <div class="item task-item" data-id="<?= $model->id ?>" data-service_type="<?=$model->service_type?>">
        <div class="header">
            <span><?= TaskHelper::generateTaskLabel($model) ?></span>
            <a class="btn btn-remove"><span class="glyphicon glyphicon-trash"></span></a>
        </div>
        <div class="info">
            <a class="do-task" href="<?= $model->link ?>" target="_blank">
                <div class="img-wrapper">
                    <img src="<?= $model->preview ?>" alt="">
                </div></a>

            <!--                                <div class="name"><strong>Оставить комментарий</strong><br>к записи на стене</div>-->
        </div>
        <div class="actions">
            <a href="<?= $model->link ?>" target="_blank" type="button" class="do-task btn btn-primary"><i class="fa fa-plus-circle"></i><?= $model->points ?> баллов</a>
            <a href="#" type="button" class="check-task btn btn-warning"><i class="fa fa-check-circle"></i>Проверить</a>
        </div>
    </div>
</div>
