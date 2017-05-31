<?php

namespace app\modules\user\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%services}}".
 *
 * @property integer $id
 * @property integer $identity_id
 * @property string $service_name
 * @property integer $user_id
 * @property User $user
 */
class Service extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%service}}';
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identity_id', 'service_name', 'user_id'], 'required'],
            [['identity_id', 'user_id'], 'integer'],
            [['service_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'identity_id' => 'Identity ID',
            'service_name' => 'Service Name',
            'user_id' => 'User ID',
        ];
    }
}