<?php

namespace app\modules\main\controllers;

use Yii;
use yii\web\Controller;

/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 24.05.17
 * Time: 20:41
 */

class PageController extends Controller
{
    /**
     * Displays contact page.
     *
     * @param string $pageName
     * @return string
     */
    public function actionView($pageName)
    {
        if ( ! Yii::$app->user->isGuest ) {
            $this->layout = '@app/views/layouts/user';
        }
        return $this->render($pageName);
    }
}