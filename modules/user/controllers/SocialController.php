<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 27.06.17
 * Time: 16:17
 */

namespace app\modules\user\controllers;


use app\modules\user\models\Task;
use app\modules\user\models\User;
use nodge\eauth\EAuth;
use nodge\eauth\ErrorException;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class SocialController extends Controller
{
    public function actionIsAuth($serviceType)
    {
        /** @var $eauth EAuth */
        $eauth = Yii::$app->get('eauth');
        $serviceName = Task::getServiceType($serviceType);
        $identity = $eauth->getIdentity($serviceName);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'authenticated' => $identity->getIsAuthenticated(),
            'serviceName' => $serviceName,
            'jsArguments' => $identity->getJsArguments()
        ];
    }


    public function actionAuth($service)
    {
        /** @var $eauth EAuth */
        $eauth = Yii::$app->get('eauth');
        $identity = $eauth->getIdentity($service);
        $identity->authenticate();
        return $eauth->redirect('/', false);
    }
}