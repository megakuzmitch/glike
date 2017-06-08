<?php

namespace app\modules\user\models;

use app\extended\eauth\VKontakteOAuth2Service;
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
    const SERVICE_TYPE_VK = 1;

    const TASK_TYPE_LIKES = 1;
    const TASK_TYPE_FRIENDS = 2;
    const TASK_TYPE_SUBSCRIBERS = 3;
    const TASK_TYPE_POSTS = 4;


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

            switch ( $this->service_type ) {
                case self::SERVICE_TYPE_VK:
                    $this->parseLinkFromVK();
                    break;
            }

            return true;
        }
        return false;
    }

    public function parseLinkFromVK()
    {
        $matches = [];
        preg_match("/^https:\/\/vk.com\/[\w\d]*\??[zw]?=?(wall|photo|video|audio)(-?\d+)_(\d+)/", $this->link, $matches);

        if ( !empty($matches) ) {
            $itemType = $matches[1];
            $ownerId = $matches[2];
            $itemId = $matches[3];

            $this->owner_id = $ownerId;
            $this->item_id = $itemId;
            $this->item_type = $itemType;
            $this->link = "https://vk.com/" . $itemType . $ownerId . "_" . $itemId;

            /**
             * @var $service VKontakteOAuth2Service
             */
            $service = Yii::$app->get('eauth')->getIdentity('vkontakte');

            switch ( $itemType ) {
                case 'photo':
                    $data = $service->getPhotosById($ownerId . '_' . $itemId);
                    $this->preview = $data[0]['src'];
                    break;

                case 'wall':
                    $data = $service->getWallById($ownerId . '_' . $itemId);
                    if ( empty($data[0]) ) {
                        return false;
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
                    $data = $service->getVideosById($ownerId . '_' . $itemId);
                    if ( empty($data['items']) ) {
                        return false;
                    }
                    $this->preview = $data['items'][0]['photo_130'];
                    break;
            }

            return true;

        } else {
            return false;
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