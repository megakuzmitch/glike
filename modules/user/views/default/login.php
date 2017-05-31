<?php
/**
 * @var $this yii\web\View
 */


use yii\helpers\Html;
$this->title = 'Вход на сайт';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-default-login">

    <div class="row centered">
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="col-lg-4 col-lg-offset-4">
            <?php echo \nodge\eauth\Widget::widget([
                'action' => Yii::$app->user->loginUrl
            ]); ?>
        </div>

    </div>



</div>