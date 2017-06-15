<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 11.05.2017
 * Time: 16:38
 */

namespace app\modules\user\controllers;


use app\extended\eauth\VKontakteOAuth2Service;
use app\modules\user\models\DoneTask;
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

        $user = Yii::$app->user->getIdentity();

        $doneTaskQuery = DoneTask::find()->select('task_id')->where(['user_id' => $user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => Task::find()
                ->where('need_count > counter')
//                ->andWhere(['NOT IN', 'id', $doneTaskQuery])
                ->orderBy('created_at DESC'),
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
        $taskIsDone = false;

        switch ($task->service_type) {
            case Task::SERVICE_TYPE_VK:
                $taskIsDone = $this->checkTaskVK($task, $response);
                break;
        }

        if ( ! $taskIsDone ) {
            return $response;
        }


        /**
         * @var $user User
         */
        $user = Yii::$app->user->getIdentity();
        $transaction = Yii::$app->db->beginTransaction();
        if ( $user->addPoints($task->points) && $task->addHit() ) {

            $doneTask = new DoneTask();
            $doneTask->user_id = $user->id;
            $doneTask->task_id = $task->id;

            if ( $doneTask->save() ) {
                $transaction->commit();
            } else {
                $transaction->rollback();
            }

        } else {
            $transaction->rollBack();
        }

        $response['done'] = true;
        $response['user_points'] = $user->points;
        $response['message'] = 'Задание выполнено!';

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
                $liked = $service->getIsLiked($itemType, $task->owner_id, $task->item_id);
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
                $itemType = $task->item_type === 'wall' ? 'post' : $task->item_type;
                $isReposted = $service->getIsReposted($itemType, $task->owner_id, $task->item_id);
                if ( ! $isReposted ) {
                    $response['message'] = 'Задание не выполнено';
                    return false;
                }
                return true;

            case Task::TASK_TYPE_COMMENT:
                $comment = $service->getLastComment($task->item_type, $task->owner_id, $task->item_id);
                if ( ! $comment ) {
                    $response['message'] = 'Задание не выполнено';
                    return false;
                }
                return true;
        }

        return false;
    }
}