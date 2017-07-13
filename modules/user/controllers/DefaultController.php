<?php

namespace app\modules\user\controllers;

use app\modules\user\models\LoginForm;
use app\modules\user\models\Service;
use app\modules\user\models\SignupForm;
use app\modules\user\models\User;
use nodge\eauth\EAuth;
use nodge\eauth\openid\ControllerBehavior;
use nodge\eauth\ServiceBase;
use Yii;
use yii\base\ErrorException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Default controller for the `user` module
 */
class DefaultController extends Controller
{
    public $layout = '@app/views/layouts/page';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'eauth' =>[
                // required to disable csrf validation on OpenID requests
                'class' => ControllerBehavior::className(),
                'only' => ['login', 'auth'],
            ],
        ];
    }

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    public function actionIndex() {
        return $this->redirect(['profile/index'], 301);
    }


    public function actionLogin($service = false)
    {
        if ( ! Yii::$app->user->isGuest ) {
            return $this->goHome();
        }

        if ( $service ) {
            /** @var $eauthService \nodge\eauth\oauth2\Service */
            $eauthService = Yii::$app->get('eauth')->getIdentity($service);
            $eauthService->setRedirectUrl( Url::previous() );
            $eauthService->setCancelUrl( Url::previous() );

            try {
                if ( $eauthService->authenticate() ) {
                    /** @var $user User */

                    $attributes = $eauthService->getAttributes();
                    $user = User::getUserByEAuth($eauthService);
                    Yii::$app->user->login($user);
                    Yii::$app->session->set('currentProfile', $eauthService->id);
                    $eauthService->redirect();
                }
                else {
                    // close popup window and redirect to cancelUrl
                    $eauthService->cancel();
                }
            } catch (ErrorException $e) {
                // save error to show it later
                Yii::$app->session->setFlash('error', 'AuthException: '.$e->getMessage());

                // close popup window and redirect to cancelUrl
//            $eauthService->cancel();
                $eauthService->redirect();
            }
        }


        $loginModel = new LoginForm();
        $signupModel = new SignupForm();

        if ( $loginModel->load(Yii::$app->request->post()) && $loginModel->login() ) {
            return $this->goHome();
        }

        if ( $signupModel->load(Yii::$app->request->post()) && $user = $signupModel->signup() ) {
            Yii::$app->user->login($user, Yii::$app->params['user.sessionDuration']);
            return $this->goHome();
        }

        return $this->render('login', ['loginModel' => $loginModel, 'signupModel' => $signupModel]);
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }



    public function actionSignup()
    {
        if ( ! Yii::$app->user->isGuest ) {
            return $this->goHome();
        }

        $model = new SignupForm();

        if ( $model->load(Yii::$app->request->post()) ) {

            if ( Yii::$app->request->isAjax ) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ( $user = $model->signup() ) {
                Yii::$app->user->login($user, Yii::$app->params['user.sessionDuration']);
                return $this->goHome();
            }
        }

        return $this->render('signup', ['model' => $model]);
    }
}
