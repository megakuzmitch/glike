<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 01.06.17
 * Time: 14:10
 */

namespace app\modules\user\models;


use app\extended\eauth\VKontakteOAuth2Service;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * Class TaskForm
 * @package app\modules\user\models
 * @var $_task Task
 */
class TaskForm extends Model
{
    private $_task;
    private $_normalizedLink;

    protected $_owner_id;
    protected $_item_id;
    protected $_item_type;

    public $id;
    public $service_type;
    public $task_type;
    public $link;
    public $points;
    public $need_count;

    public static function getServiceTypes() {
        return [
            Task::SERVICE_TYPE_VK => 'В контакте',
        ];
    }

    public static function getServiceTypeShortNames() {
        return [
            Task::SERVICE_TYPE_VK => 'vkontakte',
        ];
    }

    public static function getServiceTypeShortName($type) {
        $names = self::getServiceTypeShortNames();
        return key_exists($type, $names) ? $names[$type] : null;
    }

    public static function getTaskTypes() {
        return [
            Task::TASK_TYPE_LIKE => 'Накрутить лайки',
            Task::TASK_TYPE_SUBSCRIBE => 'Накрутить подписчиков',
            Task::TASK_TYPE_REPOST => 'Накрутить репосты',
            Task::TASK_TYPE_COMMENT => 'Накрутить комментарии',
        ];
    }


    public function rules() {
        return [
            ['link', 'validateLink'],
            [['link', 'service_type', 'task_type', 'points', 'need_count'], 'required'],
            [['points', 'service_type', 'task_type'], 'integer'],
            [['link'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'service_type' => 'Тип соцсети',
            'task_type' => 'Тип задания',
            'link' => 'Ссылка в соцсети',
            'need_count' => 'Сколько нужно',
            'points' => 'Сколько давать баллов',
        ];
    }

    public function attributePlaceholders()
    {
        return [
            'service_type' => 'Тип соцсети',
            'task_type' => 'Тип задания',
            'link' => 'Ссылка на задание в соцсети',
        ];
    }


    /**
     * @param $task Task
     * @throws \yii\base\Exception
     */
    public function syncWithTask($task)
    {
        if ( $task->isNewRecord ) {
           throw new \yii\base\Exception('Попытка синхронизации с несуществующей задачей');
        }
        $this->_task = $task;
        $this->service_type = $task->service_type;
        $this->task_type = $task->task_type;
        $this->link = $task->link;
        $this->need_count = $task->need_count;
        $this->points = $task->points;
    }


    /**
     * @return bool
     */
    public function create()
    {

        if ( ! $this->validate() ) {
            return false;
        }

        /**
         * @var $user User
         */
        $user = Yii::$app->user->getIdentity();

        if ( $user->points < $this->need_count * $this->points ) {
            Yii::$app->session->setFlash('error', 'У вас недостаточно баллов');
            return false;
        }

        $task = new Task();
        $task->service_type = $this->service_type;
        $task->task_type = (int) $this->task_type;
        $task->link = $this->getNormalizedLink();
        $task->need_count = $this->need_count;
        $task->points = $this->points;
        $task->user_id = Yii::$app->user->id;

        $task->item_type = $this->_item_type;
        $task->item_id = $this->_item_id;
        $task->owner_id = $this->_owner_id;

        if ( ! $task->save() ) {
            return false;
        }

        $user->updateCounters( [ 'points' => -($task->need_count * $task->points) ] );

        $this->syncWithTask($task);
        return true;
    }


    public function update()
    {
        if ( $this->_task === null ) {
            return false;
        }

        if ( !$this->validate() ) {
            return false;
        }

        /**
         * @var $user User
         */
        $user = Yii::$app->user->getIdentity();

        $oldTaskNeedCount = $this->_task->need_count;
        $oldTaskPoints = $this->_task->points;
        $diffPoints = $this->need_count * $this->points - $oldTaskNeedCount * $oldTaskPoints;

        if ( $user->points - $diffPoints < 0 ) {
            Yii::$app->session->setFlash('error', 'У вас недостаточно баллов');
            return false;
        }

        $this->_task->service_type = $this->service_type;
        $this->_task->task_type = $this->task_type;
        $this->_task->need_count = $this->need_count;
        $this->_task->points = $this->points;

        if ( !$this->_task->save() ) {
            Yii::$app->session->setFlash('error', 'Не удалось создать задачу');
            return false;
        }

        $user->addPoints(-$diffPoints);

        return true;
    }

    public function getIsNew()
    {
        return $this->_task === null;
    }


    public function validateLink($attribute, $params)
    {
        if ( ! $this->getIsNew() ) {
            return;
        }

        $pattern = "";
        $matches = [];
        $params['message'] = 'Неверный формат ссылки';

        switch ( $this->service_type ) {
            case Task::SERVICE_TYPE_VK:

                switch ( $this->task_type ) {
                    case Task::TASK_TYPE_LIKE:
                    case Task::TASK_TYPE_REPOST:
                    case Task::TASK_TYPE_COMMENT:
                        $pattern = "/^https:\/\/m?\.?vk.com\/[\w\d]*\??[zw]?=?(wall|photo|video|audio|product)(-?\d+)_(\d+)/";
                        preg_match($pattern, $this->link, $matches);

                        if ( empty($matches) ) {
                            $this->addError($attribute, $params['message']);
                            return;
                        }

                        $this->_item_type = $matches[1];
                        $this->_owner_id = $matches[2];
                        $this->_item_id = $matches[3];

                        break;

                    case Task::TASK_TYPE_SUBSCRIBE:
                        $pattern = "/^https:\/\/m?\.?vk.com\/([\D|\W]*)(\d*)$/";
                        preg_match($pattern, $this->link, $matches);

                        if ( empty($matches) ) {
                            $this->addError($attribute, $params['message']);
                            return;
                        }

                        $this->_item_type = $matches[1];
                        $this->_item_id = $matches[2];

                        break;
                }

            break;
        }

        $task = Task::find()->where(['link' => $this->getNormalizedLink(), 'task_type' => $this->task_type])->one();
        if ( $task !== null ) {
            $this->addError($attribute, 'Такое задание уже существует в базе данных');
        }
    }


    public function getNormalizedLink()
    {
        if ( $this->_normalizedLink !== null ) {
            return $this->_normalizedLink;
        }

        switch ( $this->service_type ) {

            case Task::SERVICE_TYPE_VK:

                $this->_normalizedLink = $this->getNormalizedLinkVK();
                break;

            default:
                $this->_normalizedLink = "";
        }

        return $this->_normalizedLink;
    }


    public function getNormalizedLinkVK()
    {
        switch ( $this->task_type ) {
            case Task::TASK_TYPE_SUBSCRIBE:
                return "https://vk.com/" . $this->_item_type . $this->_item_id;

            default:
                switch ($this->_item_type) {
                    case 'photo':
                        $res = 'https://m.vk.com/';
                        if (substr($this->_owner_id, 0, 1) == '-') {
                            $res .= 'public';
                        } else {
                            $res .= 'id';
                        }
                        $res .= substr($this->_owner_id, 1) . '?z=photo' . $this->_owner_id . '_' . $this->_item_id;
                        break;

                    case 'product':
                        $res = "https://vk.com/market?w=product" . $this->_owner_id . "_" . $this->_item_id;
                        break;

                    case 'video':
                        $res = "https://vk.com/video" . $this->_owner_id . "_" . $this->_item_id;
                        break;

                    default:
                        $res = "https://vk.com/" . $this->_item_type . $this->_owner_id . "_" . $this->_item_id;
                }

                return $res;
        }


    }
}
