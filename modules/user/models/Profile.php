<?php

namespace app\modules\user\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%profile}}".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $avatar
 */
class Profile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'avatar'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'avatar' => 'Avatar',
        ];
    }

    public function getName() {
        return $this->first_name . ' ' . $this->last_name;
    }


    public function getAvatarPhoto()
    {
        return $this->avatar === null ? '/img/default_avatar.png' : $this->avatar;
    }
}