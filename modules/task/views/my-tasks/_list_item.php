<?php
/**
 * @var $model \app\modules\user\models\Task
 */

use app\helpers\TaskHelper;
use yii\bootstrap\Html;

?>


<div class="task">

    <div class="row">
        <div class="details col-lg-8">

            <div class="row">
                <div class="preview col-lg-2 col-xs-3">
                    <a href="<?= $model->link ?>" target="_blank"><img src="<?= $model->preview ?>" alt="<?= $model->name ?>" class="img-circle"></a>
                </div>

                <div class="info col-lg-10 col-xs-9">
                    <div class="name"><a href="<?= $model->link ?>" target="_blank"><?= TaskHelper::generateTaskLabel($model) ?></a></div>
                    <div class="details">
                        Добавлено: <?= Yii::$app->formatter->asDatetime($model->created_at, "php:d.m.Y"); ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="info col-lg-4">
            <div class="status">
                <a href="#">
                    <? $width = $model->isDone() ? 100 : $model->counter / $model->need_count * 100 ?>
                    <div class="progress-bar" style="width: <?= $width ?>%"></div>
                    <div><?= $model->counter ?> / <?= $model->need_count ?> <i class="fa fa-arrow-circle-down"></i></div>
                </a>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="tools">
                <div class="pull-right">
                    <a href="#" class="btn btn-sm btn-default"><i class="fa fa-pause"></i> Приостановить</a>
                    <?= Html::a('<i class="fa fa-edit"></i> Редактировать', ['/user/my-tasks/update', 'id' => $model->id], [
                        'class' => 'btn btn-sm btn-default show-modal-btn'
                    ]) ?>
                    <?= Html::a('<i class="fa fa-remove"></i> Удалить', ['/user/my-tasks/delete', 'id' => $model->id], [
                        'title' => '<i class="fa fa-remove"></i> Удалить',
                        'aria-label'=> 'Удалить',
                        'data-confirm'=> 'Вы уверены, что хотите удалить этот элемент?',
                        'data-method'=> 'post',
                        'class' => 'btn btn-sm btn-danger'
                    ]) ?>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="do-history">

        </div>
    </div>
</div>
