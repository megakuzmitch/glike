<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 11.05.2017
 * Time: 20:40
 */

namespace app\modules\user\controllers;

use app\modules\user\models\Task;
use app\modules\user\models\TaskForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class MyTasksController extends Controller
{
    public $layout = '@app/views/layouts/user';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        $this->view->title = 'Мои задания';

        $dataProvider = new ActiveDataProvider([
            'query' => Task::find()->where(['user_id' => Yii::$app->user->id])->orderBy('created_at DESC'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $this->view->title = 'Добавление задания';

        $model = new TaskForm();

        if ( $model->load(Yii::$app->request->post()) && $model->create() ) {
            $this->redirect(['/user/my-tasks/index']);
        }

        return $this->render('create', [
            'model' => $model
        ]);

    }
}