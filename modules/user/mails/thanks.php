<?php
/** @var $model SignupForm */
use app\modules\user\models\SignupForm;
use yii\helpers\Html;
?>

Здравствуйте, вы зарегестрированы на сервисе glike.ru.
Ваш пароль: <b><?= Html::encode($model->password) ?></b>