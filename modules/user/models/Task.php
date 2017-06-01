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
 */
class Task extends ActiveRecord
{
    const SERVICE_TYPE_VK = 1;

    const TASK_TYPE_LIKES = 1;
    const TASK_TYPE_FRIENDS = 2;
    const TASK_TYPE_SUBSCRIBERS = 3;
    const TASK_TYPE_POSTS = 4;


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
            $matches = [];
            preg_match("/photo(-?\d+_?\d*)/", $this->link, $matches);

            if ( !empty($matches) ) {
                $photoId = $matches[1];

                /**
                 * @var $service VKontakteOAuth2Service
                 */
                $service = Yii::$app->get('eauth')->getIdentity('vkontakte');
                $data = $service->getPhotosById($photoId);

                $this->preview = $data[0]['src'];
            }

            return true;
        }
        return false;
    }
}