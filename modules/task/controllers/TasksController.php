<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 11.05.2017
 * Time: 16:38
 */

namespace app\modules\user\controllers;


use app\extended\eauth\GoogleOAuth2Service;
use app\extended\eauth\VKontakteOAuth2Service;
use app\modules\user\models\DoneTask;
use app\modules\user\models\Service;
use app\modules\user\models\Task;
use app\modules\user\models\TaskFilter;
use app\modules\user\models\TaskForm;
use app\modules\user\models\User;
use nodge\eauth\oauth\ServiceBase;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\helpers\Url;
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

    public function beforeAction($action)
    {
        Url::remember();
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function actionIndex()
    {
        $this->view->title = 'Заработать';

        $user = Yii::$app->user->getIdentity();
        $doneTaskQuery = DoneTask::find()->select('task_id')->where(['user_id' => $user->id]);

        $taskFilter = new TaskFilter();
        $query = $taskFilter->search();
        $query->andWhere('need_count > counter');
//        $query->andWhere(['NOT IN', 'id', $doneTaskQuery]);
        $query->orderBy('created_at DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $params = [
            'taskFilter' => $taskFilter,
            'dataProvider' => $dataProvider
        ];

        if ( Yii::$app->request->isAjax ) {
            return $this->renderPartial('_list', $params);
        }

        return $this->render('index', $params);
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
        $taskIsDone = false;

        $eauth = Yii::$app->get('eauth');
        /** @var ServiceBase $identity */
        $identity = $eauth->getIdentity(Task::getServiceType($task->service_type));
        if ( !$identity->getIsAuthenticated() ) {
            $response['message'] = 'Вы не привязаны к социальной сети';
            return $response;
        }

        switch ($task->service_type) {
            case Task::SERVICE_TYPE_VK:
                $taskIsDone = $this->checkTaskVK($task, $response);
                break;

            case Task::SERVICE_TYPE_YOUTUBE:
                $taskIsDone = $this->checkTaskYoutube($task, $response);
                break;
        }

        if ( ! $taskIsDone ) {
            return $response;
        }

        /**
         * @var $user User
         */
        $user = Yii::$app->user->getIdentity();

        if ( $task->hit($user) ) {
            $response['done'] = true;
            $response['user_points'] = $user->points;
            $response['message'] = 'Задание выполнено!';
        }

        return $response;
    }


    /**
     * @param $task Task
     * @param $response []
     * @return bool
     */
    public function checkTaskVK($task, &$response)
    {
        /**
         * @var $service VKontakteOAuth2Service
         */
        $service = Yii::$app->eauth->getIdentity('vkontakte');

        switch ( $task->task_type ) {
            case Task::TASK_TYPE_LIKE:
                $itemType = $task->item_type === 'wall' ? 'post' : $task->item_type;
                $liked = $service->getIsLiked($itemType, $task->item_id, $task->owner_id);
                if ( ! $liked ) {
                    $response['message'] = 'Задание не выполнено';
                    return false;
                }
                return true;

            case Task::TASK_TYPE_SUBSCRIBE:
                $isMember = $service->getIsMember($task->item_type, $task->item_id);
                if ( ! $isMember ) {
                    $response['message'] = 'Задание не выполнено';
                    return false;
                }
                return true;

            case Task::TASK_TYPE_REPOST:
                if ( $task->item_type === 'wall' ) {
                    $itemType = 'post';
                } elseif ($task->item_type === 'product') {
                    $itemType = 'market';
                } else {
                    $itemType = $task->item_type;
                }
                $isReposted = $service->getIsReposted($itemType, $task->item_id, $task->owner_id);
                if ( ! $isReposted ) {
                    $response['message'] = 'Задание не выполнено';
                    return false;
                }
                return true;

            case Task::TASK_TYPE_COMMENT:
                $comment = $service->getLastCommentFrom($task->item_type, $task->item_id, $task->owner_id);
                if ( ! $comment ) {
                    $response['message'] = 'Задание не выполнено';
                    return false;
                }

                return true;
        }

        return false;
    }


    /**
     * @param $task Task
     * @param $response []
     * @return bool
     */
    public function checkTaskYoutube($task, &$response)
    {
        /**
         * @var $service GoogleOAuth2Service
         */
        $service = Yii::$app->eauth->getIdentity('google');

        switch ( $task->task_type ) {
            case Task::TASK_TYPE_LIKE:
                if ( ! $service->getIsLiked($task->item_id) ) {
                    $response['message'] = 'Задание не выполнено';
                    return false;
                }
                return true;
            case Task::TASK_TYPE_COMMENT:
                $data = $service->getComments($task->item_id);

                var_dump($data); die();

                if ( ! $comment ) {
                    $response['message'] = 'Задание не выполнено';
                    return false;
                }

                return true;
        }

        return false;
    }
}