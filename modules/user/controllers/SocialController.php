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
use OAuth\Common\Storage\TokenStorageInterface;
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
        $identity->setCancelUrl( Url::previous() );
        $identity->setRedirectUrl( Url::previous() );
        if ( $identity->authenticate() ) {
            /** @var $user User */
            if ( Yii::$app->user->isGuest ) {
                $user = User::getUserByEAuth($identity);
                Yii::$app->user->login($user);
            } else {
                $user = Yii::$app->user->getIdentity();
                $user->linkService($identity);
                $user->updateProfile($identity->id, $identity->getAttributes());
            }
            $user->setCurrentProfile($identity->id);
            $identity->redirect();
        } else {
            $identity->cancel();
        }
        return $eauth->redirect('/', false);
    }


    public function actionRemove($service)
    {
        /** @var $eauth EAuth */
        $eauth = Yii::$app->get('eauth');
        /** @var \nodge\eauth\oauth\ServiceBase $identity */
        $identity = $eauth->getIdentity($service);

        $tokenStorageConfig = $eauth->getTokenStorage();
        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = Yii::createObject($tokenStorageConfig['class']);
        $tokenStorage->clearToken($identity->getServiceName());
        $identity->redirect();
    }
}