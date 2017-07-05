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
use yii\helpers\ArrayHelper;

/**
 * Class TaskForm
 * @package app\modules\user\models
 * @var $_task Task
 */
class TaskForm extends Model
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

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

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['link', 'service_type', 'task_type', 'points', 'need_count'];
        $scenarios[self::SCENARIO_UPDATE] = ['points', 'need_count'];
        return $scenarios;
    }

    public function rules() {
        return [
            ['link', 'validateLink'],
            [['link', 'service_type', 'task_type', 'points', 'need_count'], 'required'],
            [['points', 'service_type', 'task_type'], 'integer'],
            ['points', 'checkPoints'],
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


    protected function getTask()
    {
        return $this->_task;
    }


    public function getId()
    {
        return ( $task = $this->getTask() ) ? $task->id : null;
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


    public function checkPoints($attribute, $params)
    {
        /**
         * @var $user User
         */
        $user = Yii::$app->user->getIdentity();
        $task = $this->getTask();

        if ( $task ) {
            $oldTaskNeedCount = $task->need_count;
            $oldTaskPoints = $task->points;
            $diffPoints = $this->need_count * $this->points - $oldTaskNeedCount * $oldTaskPoints;
        } else {
            $diffPoints = $this->need_count * $this->points;
        }

        if ( $user->points - $diffPoints < 0 ) {
            $this->addError('points', 'У вас не хватает ' . ($diffPoints - $user->points) . ' баллов');
            $this->addError('need_count', '');
            return false;
        }

        return true;
    }


    public function validateLink($attribute, $params)
    {
        if ( ! $this->getIsNew() ) {
            return;
        }

        $params['message'] = 'Неверный формат ссылки';

        switch ( $this->service_type ) {
            case Task::SERVICE_TYPE_VK:
                $this->validateLinkVK($attribute, $params);
                break;
            case Task::SERVICE_TYPE_YOUTUBE:
                $this->validateLinkYoutube($attribute, $params);
                break;
        }

        $task = Task::find()->where(['link' => $this->getNormalizedLink(), 'task_type' => $this->task_type])->one();
        if ( $task !== null ) {
            $this->addError($attribute, 'Такое задание уже существует в базе данных');
        }
    }


    public function validateLinkVK($attribute, $params)
    {
        $urlParts = parse_url($this->link);
        $queryParts = [];
        if ( key_exists('query', $urlParts) ) {
            parse_str($urlParts['query'], $queryParts);
        }

        switch ( $this->task_type ) {
            case Task::TASK_TYPE_LIKE:
            case Task::TASK_TYPE_REPOST:
            case Task::TASK_TYPE_COMMENT:
                $pattern = "/^https:\/\/m?\.?vk.com\/[\w\d]*\??[\w\d=&]*[zw]?=?(wall|photo|video|audio|product|note)(-?\d+)_(\d+)/";
                if ( ! preg_match($pattern, $this->link, $matches) ) {
                    $this->addError($attribute, $params['message']);
                    return;
                }

                $pattern = "/(wall|photo|video|audio|product|note)(-?\d+)_(\d+)/";

                if ( ! empty($queryParts) ) {
                    if ( key_exists('z', $queryParts) ) {
                        $subject = $queryParts['z'];
                    } else if ( key_exists('w', $queryParts) ) {
                        $subject = $queryParts['w'];
                    } else {
                        $subject = $urlParts['path'];
                    }
                } else {
                    $subject = $urlParts['path'];
                }

                preg_match($pattern, $subject, $matches);

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
    }


    public function validateLinkYoutube($attribute, $params)
    {
        $urlParts = parse_url($this->link);
        $queryParts = [];
        $matches = [];
        if ( key_exists('query', $urlParts) ) {
            parse_str($urlParts['query'], $queryParts);
        }

        switch ( $this->task_type ) {
            case Task::TASK_TYPE_LIKE:
            case Task::TASK_TYPE_REPOST:
            case Task::TASK_TYPE_COMMENT:
                $pattern = "/^https:\/\/(www)?\.?youtube.com\/watch\?v=([a-zA-Z1-9_\-]+)/";
                if ( ! preg_match($pattern, $this->link, $matches) ) {
                    $this->addError($attribute, $params['message']);
                    return;
                }

                $this->_item_id = $matches[2];
                $this->_item_type = 'video';
                break;

            case Task::TASK_TYPE_SUBSCRIBE:
                $pattern = "/^https:\/\/m?\.?vk.com\/([\D|\W]*)(\d*)$/";
                $this->addError($attribute, $params['message']);
                break;
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
            case Task::SERVICE_TYPE_YOUTUBE:
                $this->_normalizedLink = $this->getNormalizedLinkYoutube();
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


    public function getNormalizedLinkYoutube()
    {
        switch ( $this->task_type ) {
            case Task::TASK_TYPE_LIKE:
            case Task::TASK_TYPE_COMMENT:
            case Task::TASK_TYPE_VIEWS:
                return 'https://youtube.com/watch?v=' . $this->_item_id;
                break;
            case Task::TASK_TYPE_SUBSCRIBE:
                return $this->link;
                break;
        }
        return false;
    }
}
