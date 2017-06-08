<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 11.05.2017
 * Time: 16:38
 */

namespace app\modules\user\controllers;


use app\extended\eauth\VKontakteOAuth2Service;
use app\modules\user\models\Service;
use app\modules\user\models\Task;
use app\modules\user\models\TaskForm;
use app\modules\user\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class TasksController extends Controller
{
    public $layout = '@app/views/layouts/user';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['check'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'verbs' => ['POST'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        $this->view->title = 'Заработать';

        $dataProvider = new ActiveDataProvider([
            'query' => Task::find()->orderBy('created_at DESC'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }


    public function actionCheck()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = Yii::$app->request->post();

        /**
         * @var $task Task
         */
        $task = Task::findOne($data['id']);

        if ( $task === null ) {
            throw new Exception('Task not found');
        }

        $response = [ 'done' => false, 'message' => 'Задание не выполнено' ];

        switch ($task->service_type) {
            case Task::SERVICE_TYPE_VK:
                $this->checkTaskVK($task, $response);
                break;
        }

        return $response;
    }


    /**
     * @param $task Task
     * @param $response []
     */
    public function checkTaskVK($task, &$response)
    {
        /**
         * @var $service VKontakteOAuth2Service
         */
        $service = Yii::$app->eauth->getIdentity('vkontakte');

        $itemType = $task->item_type;
        if ( $itemType === 'wall' ) $itemType = 'post';
        $data = $service->getIsLiked($itemType, $task->owner_id, $task->item_id);

        if ( $data['liked'] === 1 ) {

            /**
             * @var $user User
             */
            $user = Yii::$app->user->getIdentity();
            $user->addPoints($task->points);
            $task->addHit();

            $response['done'] = true;
            $response['user_points'] = $user->points;
            $response['message'] = 'Задание выполнено!';

        } else {

            $response['message'] = 'Задание не выполнено';

        }
    }
}