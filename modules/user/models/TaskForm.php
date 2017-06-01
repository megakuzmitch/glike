<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 01.06.17
 * Time: 14:10
 */

namespace app\modules\user\models;


use Yii;
use yii\base\Model;
use yii\db\Exception;

class TaskForm extends Model
{
    public $name;
    public $description;
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
            Task::SERVICE_TYPE_VK => 'vk',
        ];
    }

    public static function getServiceTypeShortName($type) {
        $names = self::getServiceTypeShortNames();
        return key_exists($type, $names) ? $names[$type] : null;
    }

    public static function getTaskTypes() {
        return [
            Task::TASK_TYPE_LIKES => 'Накрутить лайки',
//            Task::TASK_TYPE_FRIENDS => 'Накрутить друзей',
//            Task::TASK_TYPE_SUBSCRIBERS => 'Накрутить подписчиков',
//            Task::TASK_TYPE_POSTS => 'Накрутить репосты',
        ];
    }


    public function rules() {
        return [
            [['link', 'service_type', 'task_type', 'points', 'need_count'], 'required'],
            [['points', 'service_type', 'task_type'], 'integer'],
            [['name', 'description', 'link'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'description' => 'Краткое описание',
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
            'name' => 'Название',
            'description' => 'Краткое описание',
            'service_type' => 'Тип соцсети',
            'task_type' => 'Тип задания',
            'link' => 'Ссылка на задание в соцсети',
        ];
    }


    public function create()
    {

        if ( $this->validate() ) {

            $task = new Task();
            $task->name = $this->name;
            $task->description = $this->description;
            $task->service_type = $this->service_type;
            $task->task_type = $this->task_type;
            $task->link = $this->link;
            $task->need_count = $this->need_count;
            $task->points = $this->points;
            $task->user_id = Yii::$app->user->id;

            if ( !$task->save() ) {
                throw new Exception('Невозможно создать задачу');
            }

            return true;
        }

        return false;
    }
}