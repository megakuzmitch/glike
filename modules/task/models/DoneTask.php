<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 09.06.17
 * Time: 14:59
 */

namespace app\modules\user\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class DoneTask
 * @package app\modules\user\models
 * @property $user_id integer
 * @property $task_id integer
 */
class DoneTask extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%done_tasks}}';
    }


    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}