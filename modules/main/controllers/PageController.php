<?php

namespace app\modules\main\controllers;

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
     * @param null $id
     * @return string
     */
    public function actionView($id = null)
    {
        return $this->render('index');
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionHelp()
    {
        return $this->render('help');
    }
}