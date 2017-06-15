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
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
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
                        'actions' => ['index', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
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

    public function actionUpdate($id)
    {
        $this->view->title = 'Редактирование задания';

        $task = Task::findOne($id);
        if ( $task === null ) {
            throw new Exception('Такого задания не существует');
        }

        $model = new TaskForm();
        $model->syncWithTask($task);

        if ( $model->load(Yii::$app->request->post()) && $model->update() ) {
            $this->redirect(['/user/my-tasks/index']);
        }

        return $this->render('update', [
            'model' => $model
        ]);

    }


    public function actionDelete($id)
    {
        $model = Task::findOne($id);

        if ( $model ) {
            $model->delete();
        }

        $this->redirect(['/user/my-tasks/index']);
    }
}