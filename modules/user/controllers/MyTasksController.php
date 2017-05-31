<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 11.05.2017
 * Time: 20:40
 */

namespace app\modules\user\controllers;


use yii\filters\AccessControl;
use yii\web\Controller;

class MyTasksController extends Controller
{
    public $layout = '@app/views/layouts/user';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}