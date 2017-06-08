<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 11.05.2017
 * Time: 20:43
 *
 * @var \yii\web\View $this
 * @var $content
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use kartik\select2\Select2;
use yii\helpers\Url;

?>


<div class="user-my-tasks">

    <div class="row">
        <div class="col-lg-12">
            <div class="tools pull-right">
                <a href="<?=Url::to(['/user/my-tasks/create'])?>" class="btn btn-success">Добавить задание</a>

                <!-- Modal -->
                <div class="modal" id="task-add-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Добавление задания - В контакте</h4>
                            </div>
                            <form action="<?php echo Url::current() ?>" method="post">
                                <div class="modal-body">

                                    <ul class="nav nav-pills">
                                        <li role="presentation" class="active"><a href="#">В контакте</a></li>
                                        <li role="presentation"><a href="#">Youtube</a></li>
                                        <li role="presentation"><a href="#">Instagramm</a></li>
                                        <li role="presentation"><a href="#">Twitter</a></li>
                                        <li role="presentation"><a href="#">Одноклассники</a></li>
                                    </ul>

                                    <ul class="nav nav-pills nav-pills-sm">
                                        <li role="presentation" class="active"><a href="#">Поставить лайк</a></li>
                                        <li role="presentation" class=""><a href="#">Вступить в группу</a></li>
                                        <li role="presentation" class=""><a href="#">Добавить в друзья</a></li>
                                        <li role="presentation" class=""><a href="#">Сделать репост</a></li>
                                    </ul>

                                    <div class="form-group">
                                        <label for="task_link">Ссылка</label>
                                        <input type="text" class="form-control" id="task_link" placeholder="Ссылка">
                                    </div>
                                    <div class="form-group">
                                        <label for="task_desc">Описание</label>
                                        <input type="text" class="form-control" id="task_desc" placeholder="Описание">
                                    </div>

                                    <div class="form-group">
                                        <label for="task_type">Тип</label>
                                        <?php echo Select2::widget([
                                            'name' => 'task_type',
                                            'value' => '',
                                            'data' => ['Лайкнуть фото', 'Оставить комментарий', 'Сделать репост']
                                        ]) ?>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                                    <button type="button" class="btn btn-primary">Сохранить</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <ul class="nav nav-pills">
        <li role="presentation" class="active"><a href="#vk-tab" aria-controls="vk-tab" role="tab" data-toggle="tab">В контакте</a></li>
        <li role="presentation"><a href="#youtube-tab" aria-controls="youtube-tab" role="tab" data-toggle="tab">Youtube</a></li>
        <li role="presentation"><a href="#instagramm-tab" aria-controls="instagramm-tab" role="tab" data-toggle="tab">Instagramm</a></li>
        <li role="presentation"><a href="#twitter-tab" aria-controls="twitter-tab" role="tab" data-toggle="tab">Twitter</a></li>
        <li role="presentation"><a href="#ok-tab" aria-controls="ok-tab" role="tab" data-toggle="tab">Одноклассники</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="vk-tab">


            <?= \yii\widgets\ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_list',
                'options' => [
                    'class' => 'my-tasks-list'
                ]
            ]) ?>


        </div>
        <div role="tabpanel" class="tab-pane" id="youtube-tab">...</div>
        <div role="tabpanel" class="tab-pane" id="instagramm-tab">...</div>
        <div role="tabpanel" class="tab-pane" id="twitter-tab">...</div>
        <div role="tabpanel" class="tab-pane" id="ok-tab">...</div>
    </div>

</div>
