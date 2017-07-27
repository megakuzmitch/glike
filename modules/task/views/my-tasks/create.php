<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 01.06.17
 * Time: 14:24
 *
 * @var $model TaskForm
 */

use app\modules\user\models\TaskForm;

?>

<div class="user-task-create">

    <div class="row">
        <div class="col-lg-12">

            <?= $this->render('_form', ['model' => $model]); ?>

        </div>
    </div>
</div>
