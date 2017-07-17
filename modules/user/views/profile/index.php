<?php

use app\widgets\EAuthWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = 'Профиль пользователя';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-profile">

    <p>
        <?= Html::a('Редактировать', ['update'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сменить пароль', ['password-change'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email',
        ],
    ]) ?>


    <div class="row">
        <div class="col-lg-6">
            <h3>Привязка к соцсетям:</h3>
            <?= EAuthWidget::widget(['action' => '/user/social/auth']) ?>

        </div>
    </div>

</div>