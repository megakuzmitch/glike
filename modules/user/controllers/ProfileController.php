<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 08.05.2017
 * Time: 23:59
 */

namespace app\modules\user\controllers;


use app\modules\user\models\PasswordChangeForm;
use app\modules\user\models\ProfileUpdateForm;
use app\modules\user\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class ProfileController extends Controller
{
    public $layout = '@app/views/layouts/user';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->getIdentity();
        Url::remember();
        $eauthConfig = Yii::$app->get('eauth')->services;
        $services = [];
        foreach ( $eauthConfig as $key => $service ) {
            $services[$key] = Yii::$app->get('eauth')->getIdentity($key);
        }
        return $this->render('index', [
            'model' => $user,
            'services' => $services
        ]);
    }

    public function actionUpdate()
    {
        $user = $this->findModel();
        $model = new ProfileUpdateForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionPasswordChange()
    {
        $user = $this->findModel();
        $model = new PasswordChangeForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('passwordChange', [
                'model' => $model,
            ]);
        }
    }


    public function actionCurrent()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Yii::$app->user->identity;
        $profile = $user->getCurrentProfile();
        return [
            'name' => $profile->name,
            'avatar' => $profile->avatar
        ];
    }


    /**
     * @return User the loaded model
     */
    private function findModel()
    {
        return User::findOne(Yii::$app->user->identity->getId());
    }
}