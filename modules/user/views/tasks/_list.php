<?php
/**
 * @var $model \app\modules\user\models\Task
 */
?>


<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
    <div class="item">
        <a class="btn btn-remove"><span class="glyphicon glyphicon-trash"></span></a>
        <div class="info">
            <img src="<?= $model->preview ?>" alt="" class="img-circle">
            <!--                                <div class="name"><strong>Оставить комментарий</strong><br>к записи на стене</div>-->
        </div>
        <div class="actions">
            <a href="#" type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i><?= $model->points ?> баллов</a>
        </div>
    </div>
</div>
