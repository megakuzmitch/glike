<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 11.05.2017
 * Time: 16:38
 */

namespace app\modules\user\controllers;


use yii\web\Controller;

class TasksController extends Controller
{
    public $layout = '@app/views/layouts/user';

    public function actionIndex()
    {
        return $this->render('index');
    }
}