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

    <div class="row">
        <div class="col-lg-6">

            <?php echo EAuthWidget::widget([
                'popup' => true,
                'action' => Url::to(['/user/default/auth'])
            ]); ?>

        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email',
        ],
    ]) ?>

</div>