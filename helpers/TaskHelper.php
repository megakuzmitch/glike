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

        switch ( $task->task_type ) {
            case Task::TASK_TYPE_LIKE:
                $action = 'Поствить лайк';
                break;
            case Task::TASK_TYPE_REPOST:
                $action = 'Рассказать друзьям';
                break;
            case Task::TASK_TYPE_COMMENT:
                $action = 'Оставить комментарий';
                break;
            case Task::TASK_TYPE_SUBSCRIBE:
                if ( $task->item_type == 'group' ) {
                    $action = 'Вступить';
                } else {
                    $action = 'Подписаться';
                }
                break;
            case Task::TASK_TYPE_VIEWS:
                $action = 'Просмотреть';
                break;
        }


        switch ($task->service_type) {
            case Task::SERVICE_TYPE_VK:

                switch ($task->task_type) {
                    case Task::TASK_TYPE_LIKE:
                        $label .= "<strong>Поставить лайк</strong> ";
                        switch ($task->item_type) {
                            case 'photo':
                                $label .= 'к фотографии';
                                break;
                            case 'wall':
                                $label .= 'к записи на стене';
                                break;
                            case 'product':
                                $label .= 'к товару';
                                break;
                            case 'video':
                                $label .= 'к видео';
                                break;
                        }
                        break;

                    case Task::TASK_TYPE_REPOST:
                        $label .= "<strong>Рассказать друзьям</strong> ";
                        switch ($task->item_type) {
                            case 'photo':
                                $label .= 'о фотографии';
                                break;
                            case 'wall':
                                $label .= 'о записи на стене';
                                break;
                            case 'product':
                                $label .= 'о товаре';
                                break;
                        }
                        break;

                    case Task::TASK_TYPE_COMMENT:
                        $label .= "<strong>Оставить комментарий</strong> ";
                        switch ($task->item_type) {
                            case 'photo':
                                $label .= 'к фотографии';
                                break;
                            case 'wall':
                                $label .= 'к записи на стене';
                                break;
                            case 'product':
                                $label .= 'к товару';
                                break;
                        }
                        break;

                    case Task::TASK_TYPE_SUBSCRIBE:

                        switch ( $task->item_type ) {
                            case 'user':
                                $label .= "<strong>Подписаться</strong> на пользователя";
                                break;
                            case 'group':
                                $label .= "<strong>Вступить</strong> в группу";
                                break;
                            case 'page':
                                $label .= "<strong>Подписаться</strong> на страницу";
                                break;
                        }

                        break;
                }
                break;

            case Task::SERVICE_TYPE_YOUTUBE:
                switch ($task->task_type) {
                    case Task::TASK_TYPE_LIKE:
                        $label .= "<strong>Поставить лайк</strong> ";
                        switch ($task->item_type) {
                            case 'video':
                            default:
                                $label .= 'к видео';
                                break;
                        }
                        break;

                    case Task::TASK_TYPE_REPOST:
                        $label .= "<strong>Рассказать друзьям</strong> ";
                        switch ($task->item_type) {
                            case 'video':
                            default:
                                $label .= 'о видео';
                                break;
                        }
                        break;

                    case Task::TASK_TYPE_COMMENT:
                        $label .= "<strong>Оставить комментарий</strong> ";
                        switch ($task->item_type) {
                            case 'video':
                            default:
                                $label .= 'к видео';
                                break;
                        }
                        break;

//                    case Task::TASK_TYPE_SUBSCRIBE:
//
//                        switch ( $task->item_type ) {
//                            case 'user':
//                                $label .= "<strong>Подписаться</strong> на пользователя";
//                                break;
//                            case 'group':
//                                $label .= "<strong>Вступить</strong> в группу";
//                                break;
//                            case 'page':
//                                $label .= "<strong>Подписаться</strong> на страницу";
//                                break;
//                        }
//
//                        break;
                }
                break;
        }

        return $label;
    }

}