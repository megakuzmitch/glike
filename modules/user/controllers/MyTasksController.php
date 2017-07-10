<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 11.05.2017
 * Time: 20:40
 */

namespace app\modules\user\controllers;

use app\modules\user\models\Task;
use app\modules\user\models\TaskFilter;
use app\modules\user\models\TaskForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

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
                        'actions' => ['index', 'validate', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'validate' => ['post']
                ],
            ],
        ];
    }


    public function actionIndex()
    {
//        $this->view->title = 'Мои задания';

        $taskFilter = new TaskFilter();
        $query = $taskFilter->search();
        $query->andWhere(['user_id' => Yii::$app->user->id]);
        $query->orderBy('created_at DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $params = [
            'taskFilter' => $taskFilter,
            'dataProvider' => $dataProvider
        ];


        if ( Yii::$app->request->isAjax ) {
            return $this->renderPartial('_list', $params);
        }

        $params['taskFormModel'] = new TaskForm();
        return $this->render('index', $params);
    }


    public function actionValidate($id = null)
    {
        $model = new TaskForm();
        if ( $id ) {
            $model->syncWithTask(Task::findOne($id));
        }
        if ( $model->load(Yii::$app->request->post()) ) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        return false;
    }


    /**
     * @return array|string
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new TaskForm();

        if ( $model->load($request->post()) ) {

            if ( $model->create() ) {
                if ( $request->isAjax ) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'success' => true,
                        'userCounters' => ['points' => Yii::$app->user->getIdentity()->points]
                    ];
                } else {
                    $this->redirect(['/user/my-tasks/index']);
                }
            } else {
                if ( $request->isAjax ) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'success' => false,
                    ];
                }
            }
        } else if ( $request->isAjax ) {
            return $this->renderAjax('create', ['model' => $model]);
        }

        $this->view->title = 'Добавление задания';
        return $this->render('create', [
            'model' => $model
        ]);

    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $task = Task::findOne($id);
        if ( $task === null ) {
            throw new Exception('Такого задания не существует');
        }
        $model = new TaskForm();
        $model->syncWithTask($task);
        $model->scenario = TaskForm::SCENARIO_UPDATE;

        if ( $model->load($request->post()) ) {
            if ( $model->update() ) {
                if ( $request->isAjax ) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'success' => true,
                        'userCounters' => ['points' => Yii::$app->user->getIdentity()->points]
                    ];
                } else {
                    $this->redirect(['/user/my-tasks/index']);
                }
            }
        } else if ( $request->isAjax ) {
            return $this->renderAjax('update', ['model' => $model]);
        }

        $this->view->title = 'Редактирование задания';
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

        $this->redirect(['index']);
    }
}