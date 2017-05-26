<?php

namespace app\modules\main\controllers;

use Yii;
use yii\web\Controller;

/**
 * Default controller for the `main` module
 */
class DefaultController extends Controller
{
    public $layout = '@app/views/layouts/page';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if ( ! Yii::$app->user->isGuest ) {
            $this->redirect(['/user/tasks/index']);
        }

        $this->layout = "@app/views/layouts/main";
        return $this->render('index');
    }
}
