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
use yii\web\Controller;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('profile');

        return $this->render('index', [
            'model' => $this->findModel(),
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

    /**
     * @return User the loaded model
     */
    private function findModel()
    {
        return User::findOne(Yii::$app->user->identity->getId());
    }
}