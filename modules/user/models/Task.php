<?php

namespace app\modules\user\models;

use app\extended\eauth\VKontakteOAuth2Service;
use nodge\eauth\ErrorException;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%task}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $link
 * @property integer $points
 * @property integer $service_type
 * @property integer $task_type
 * @property integer $need_count
 * @property integer $counter
 * @property integer $user_id
 * @property integer $preview
 * @property string $owner_id
 * @property string $item_id
 * @property string $item_type
 */
class Task extends ActiveRecord
{
    const SERVICE_TYPE_VK       = 1;

    const TASK_TYPE_LIKE        = 1;
    const TASK_TYPE_SUBSCRIBE   = 2;
    const TASK_TYPE_REPOST      = 3;
    const TASK_TYPE_COMMENT     = 4;


    public static function getServiceTypeNames() {
        return [
            Task::SERVICE_TYPE_VK => 'vkontakte',
        ];
    }


    public function getServiceTypeName() {
        $names = self::getServiceTypeNames();
        return key_exists($this->service_type, $names) ? $names[$this->service_type] : null;
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%task}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link'], 'unique'],
            [['link', 'service_type', 'task_type', 'user_id'], 'required'],
            [['points', 'service_type', 'task_type', 'user_id', 'need_count'], 'integer'],
            [['name', 'description', 'link'], 'string', 'max' => 255],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'link' => 'Link',
            'points' => 'Points',
            'service_type' => 'Service Type',
            'task_type' => 'Task Type',
            'user_id' => 'User ID',
        ];
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ( $insert ) {
                $this->loadPreview();
            }
            return true;
        }
        return false;
    }


    public function afterDelete()
    {
        /**
         * @var $user User
         */
        $user = Yii::$app->user->getIdentity();
        $unusedPonts = ($this->need_count - $this->counter) * $this->points;
        $user->addPoints($unusedPonts);

        DoneTask::deleteAll(['task_id' => $this->id]);

        parent::afterDelete();
    }


    public function loadPreview()
    {
        switch ( $this->service_type ) {
            case TASK::SERVICE_TYPE_VK:
                $this->loadPreviewVK();
                break;
        }
    }



    public function loadPreviewVK()
    {
        /**
         * @var $service VKontakteOAuth2Service
         */
        $service = Yii::$app->get('eauth')->getIdentity('vkontakte');

        switch ( $this->task_type ) {
            case self::TASK_TYPE_LIKE:
            case self::TASK_TYPE_REPOST:
            case self::TASK_TYPE_COMMENT:
                switch ( $this->item_type ) {
                    case 'photo':
                        $data = $service->getPhotosById($this->owner_id . '_' . $this->item_id);

                        $this->preview = $data[0]['photo_130'];
                        break;

                    case 'wall':
                        $data = $service->getWallById($this->owner_id . '_' . $this->item_id);
                        if ( empty($data[0]) ) {
                            break;
                        }
                        $attachments = $data[0]['attachments'];
                        foreach ( $attachments as $attachment ) {
                            if ( $attachment['type'] !== 'photo' ) {
                                continue;
                            }
                            $this->preview = $attachment['photo']['photo_130'];
                        }
                        break;

                    case 'video':
                        $data = $service->getVideosById($this->owner_id . '_' . $this->item_id);
                        if ( empty($data['items']) ) {
                            break;
                        }
                        $this->preview = $data['items'][0]['photo_130'];
                        break;

                    case 'product':
                        $data = $service->getProductById($this->owner_id . '_' . $this->item_id);

                        if ( $data['count'] > 0 ) {
                            $this->preview = $data['items'][0]['thumb_photo'];
                        }
                        break;
                }
                break;

            case self::TASK_TYPE_SUBSCRIBE:
                // Определяем тип объекта для подписки (пользователь, группа или паблик)
                $data = $service->getUsers($this->item_type . $this->item_id);
                if ( $data ) {
                    $this->item_type = 'user';
                    $this->item_id = $data[0]['id'];
                    $this->preview = $data[0]['photo_200'];
                    return;
                }
                $groupId = ($this->item_type == 'club' || $this->item_type == 'public')
                    ? $this->item_id
                    : $this->item_type . $this->item_id;

                $data = $service->getGroups($groupId);
                if ( $data ) {
                    $this->item_type = $data[0]['type'];
                    $this->item_id = $data[0]['id'];
                    $this->preview = $data[0]['photo_200'];
                    return;
                }
                break;
        }
    }



    public function addHit()
    {
        return $this->updateCounters(['counter' => 1]);
    }

    public function isDone()
    {
        return $this->counter >= $this->need_count;
    }
}