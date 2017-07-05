<?php

namespace app\modules\main\controllers;

use Yii;
use yii\web\Controller;

/**
 * Default controller for the `main` module
 */
class DefaultController extends Controller
{
//    public $layout = '@app/views/layouts/user';

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


    public function beforeAction($action)
    {
        if ( parent::beforeAction($action) ) {

            if ( Yii::$app->getErrorHandler()->exception !== null ) {
                $this->layout = Yii::$app->user->isGuest
                    ? '@app/views/layouts/simple'
                    : '@app/views/layouts/user';
            }

            return true;
        }

        return false;
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
        return $this->render('index');
    }
}
