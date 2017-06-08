<?php
/**
 * @var $model \app\modules\user\models\Task
 */
?>


<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
    <div class="item">
        <a class="btn btn-remove"><span class="glyphicon glyphicon-trash"></span></a>
        <div class="info">
            <a class="do-task" data-id="<?= $model->id ?>" href="<?= $model->link ?>" target="_blank"><img src="<?= $model->preview ?>" alt="" class="img-circle"></a>

            <!--                                <div class="name"><strong>Оставить комментарий</strong><br>к записи на стене</div>-->
        </div>
        <div class="actions">
            <a data-id="<?= $model->id ?>" href="<?= $model->link ?>" target="_blank" type="button" class="do-task btn btn-primary"><i class="fa fa-plus-circle"></i><?= $model->points ?> баллов</a>
        </div>
    </div>
</div>
