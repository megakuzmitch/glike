<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 14.06.17
 * Time: 10:35
 *
 * @var $model TaskForm
 */

use app\modules\user\models\TaskForm;

?>

<div class="user-task-update">

    <div class="row">
        <div class="col-lg-12">

            <?= $this->render('_form', ['model' => $model]); ?>

        </div>
    </div>
</div>