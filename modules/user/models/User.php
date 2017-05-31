<?php

namespace app\modules\user\models;

use nodge\eauth\ErrorException;
use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $auth_key
 * @property string $email_confirm_token
 * @property string $email
 */
class User extends ActiveRecord implements IdentityInterface
{

    protected $_profile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function getServices()
    {
        return $this->hasMany(Service::className(), ['user_id' => 'id']);
    }

    public function getProfile() {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            ['username', 'match', 'pattern' => '#^[\w_-]+$#is'],
//            ['username', 'unique', 'targetClass' => self::className(), 'message' => 'This username has already been taken.'],
//            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'email'],
            ['email', 'unique', 'targetClass' => self::className(), 'message' => 'This email address has already been taken.'],
            ['email', 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлён',
            'email' => 'Email',
        ];
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);

//        if (Yii::$app->getSession()->has('user-'.$id)) {
//            return new self(Yii::$app->getSession()->get('user-'.$id));
//        }
//        else {
//            return isset(self::$users[$id]) ? new self(self::$users[$id]) : null;
//        }
    }

    /**
     * @param \nodge\eauth\ServiceBase $service
     * @return User
     * @throws \nodge\eauth\ErrorException
     */
    public static function findByEAuth($service) {
        if (!$service->getIsAuthenticated()) {
            throw new ErrorException('EAuth user should be authenticated before creating identity.');
        }

        $identityAttributes = [
            'email' => $service->getAttribute('email'),
        ];
        $profileAttributes = [
            'first_name' => $service->getAttribute('first_name'),
            'last_name' => $service->getAttribute('last_name'),
            'avatar' => $service->getAttribute('avatar'),
        ];


        /**
         * @var $identity User
         */
        $identity = self::find()
            ->joinWith('services')
            ->where([
                '{{%service}}.service_name' => $service->getServiceName(),
                '{{%service}}.identity_id' => $service->getId()
            ])
            ->limit(1)
            ->one();

        if ( $identity !== null) {
            $identity->updateProfile($profileAttributes);
            return $identity;
        }

        $identity = new self($identityAttributes);
        $transaction = self::getDb()->beginTransaction();

        if ( $identity->save() ) {

            $serviceRecord = new Service();
            $serviceRecord->identity_id = $service->getId();
            $serviceRecord->service_name = $service->getServiceName();
            $serviceRecord->user_id = $identity->id;

            $profile = new Profile();
            $profile->attributes = $profileAttributes;

            if ( $serviceRecord->save() && $profile->save() ) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
                throw new ErrorException("Identity don't create");
            }

        } else {
            throw new ErrorException("Identity don't create");
        }

//        $id = $service->getServiceName().'-'.$service->getId();

//        Yii::$app->getSession()->set('user-'.$id, $attributes);
        return $identity;
    }


    /**
     * @param $attributes
     * @return bool|false|int
     */
    public function updateProfile($attributes)
    {
        if ( !is_array($attributes) || $this->isNewRecord ) {
            return false;
        }

        /**
         * @var $profile ActiveRecord
         */

        if ( $this->profile === null ) {
            $profile = new Profile();
            $profile->user_id = $this->id;
        } else {
            $profile = $this->profile;
        }

        foreach ( $attributes as $name => $value ) {
            if ( $profile->hasAttribute($name) && empty( $profile->getAttribute($name) ) ) {
                $profile->setAttribute($name, $value);
            }
        }

        return $profile->save();
    }


    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function setAuthKey($key)
    {
        $this->auth_key = $key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }

    /**
     * @param string $email_confirm_token
     * @return static|null
     */
    public static function findByEmailConfirmToken($email_confirm_token)
    {
        return static::findOne(['email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_WAIT]);
    }

    /**
     * Generates email confirmation token
     */
    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Removes email confirmation token
     */
    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }
}
