<?php
namespace app\helpers;

use app\modules\user\models\Task;

/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 08.06.17
 * Time: 20:42
 */
class TaskHelper
{

    /**
     * @param $task Task
     * @return string
     */
    public static function generateTaskLabel($task)
    {
        $label = "";

        switch ($task->service_type) {
            case Task::SERVICE_TYPE_VK:

                switch ($task->task_type) {
                    case Task::TASK_TYPE_LIKES:
                        $label .= "<strong>Поставить лайк</strong> ";
                        break;
                }

                switch ($task->item_type) {
                    case 'photo':
                        $label .= 'к фотографии на стене';
                        break;
                    case 'wall':
                        $label .= 'к записи на стене';
                        break;
                }

                break;
        }

        return $label;
    }

}