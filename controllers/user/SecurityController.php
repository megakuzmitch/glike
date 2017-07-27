<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 19.07.17
 * Time: 11:43
 */

namespace app\controllers\user;

use app\models\user\RegistrationForm;
use dektrium\user\controllers\RegistrationController;
use app\models\user\LoginForm;
use dektrium\user\models\Account;
use yii\authclient\ClientInterface;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SecurityController extends \dektrium\user\controllers\SecurityController
{
    protected function _login(LoginForm $model)
    {
        $loginEvent = $this->getFormEvent($model);
        $this->trigger(self::EVENT_BEFORE_LOGIN, $loginEvent);
        if ( $model->login() ) {
            $this->trigger(self::EVENT_AFTER_LOGIN, $loginEvent);
            return true;
        }
        return false;
    }


    /**
     * Displays the login page.
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionLogin()
    {
        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException();
        }

        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $loginTabActive = true;

        /** @var LoginForm $loginModel */
        $loginModel = \Yii::createObject(LoginForm::className());
        $this->performAjaxValidation($loginModel);

        if ($loginModel->load(\Yii::$app->getRequest()->post())) {
            if ( $this->_login($loginModel) ) {
                return $this->goBack();
            }
        }

        /** @var RegistrationForm $registrationModel */
        $registrationModel = \Yii::createObject(RegistrationForm::className());
        $this->performAjaxValidation($registrationModel);

        if ($registrationModel->load(\Yii::$app->request->post())) {
            $loginTabActive = false;
            $event = $this->getFormEvent($registrationModel);
            $this->trigger(RegistrationController::EVENT_BEFORE_REGISTER, $event);
            if ( $registrationModel->register() ) {

                $loginModel->login = $registrationModel->email;
                $loginModel->password = $registrationModel->password;

                if ( $this->_login($loginModel) ) {
                    return $this->goBack();
                }

                $this->trigger(RegistrationController::EVENT_AFTER_REGISTER, $event);
                return $this->goBack();
            }
        }


        return $this->render('login', [
            'loginModel'  => $loginModel,
            'registrationModel'  => $registrationModel,
            'loginTabActive' => $loginTabActive,
            'module' => $this->module,
        ]);
    }
}