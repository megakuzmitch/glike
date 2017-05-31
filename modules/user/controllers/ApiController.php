<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 29.05.17
 * Time: 16:10
 */

namespace app\modules\user\controllers;


use nodge\eauth\EAuth;
use Yii;
use yii\web\Controller;

class ApiController extends Controller
{

    public function actionGet()
    {
        return $this->asJson(Yii::$app->eauth);
    }
}