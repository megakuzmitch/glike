<?php
/**
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model app\modules\user\models\LoginForm
 */

use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Вход на сайт';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-default-login">

    <div class="row centered">
        <h1><?= Html::encode($this->title) ?></h1>

        <?php Alert::widget(); ?>

        <div class="col-lg-4 col-lg-offset-4">
            <?php echo \nodge\eauth\Widget::widget([
                'action' => Yii::$app->user->loginUrl
            ]); ?>
        </div>

    </div>



</div>