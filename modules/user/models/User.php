<?php

namespace app\modules\user\models;

use nodge\eauth\ErrorException;
use nodge\eauth\oauth\ServiceBase;
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
 * @property string $password_hash
 * @property string $password_reset_token
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_WAIT = 2;
    const STATUS_BLOCKED = 3;

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
     * @param $email
     * @return User|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @param $socialService \nodge\eauth\oauth2\Service
     * @return Service
     */
    public function linkService($socialService)
    {
        $serviceRecord = new Service();
        $serviceRecord->service_name = $socialService->getServiceName();
        $serviceRecord->identity_id = $socialService->getId();
        $serviceRecord->user_id = $this->id;

        if ( $serviceRecord->save() ) {
            return $serviceRecord;
        }
        return null;
    }


    public function getIsLinked(ServiceBase $socialService)
    {
        foreach ( $this->services as $service ) {

            if ( $socialService->getServiceName() == $service->service_name ) {

//                if ( ! $socialService->getIsAuthenticated() ) {
//                    $socialService->authenticate();
//                }

                return true;
            }
        }

        return false;
    }


    public function getSocialId($serviceName)
    {
        $services = $this->services;

        foreach ( $services as $service ) {
            if ( $serviceName == $service->service_name ) {
                return $service->identity_id;
            }
        }

        return null;
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
        $identity->points = 10000;
        $transaction = self::getDb()->beginTransaction();

        if ( $identity->save() ) {

            $serviceRecord = new Service();
            $serviceRecord->identity_id = $service->getId();
            $serviceRecord->service_name = $service->getServiceName();
            $serviceRecord->user_id = $identity->id;

            $profile = new Profile();
            $profile->attributes = $profileAttributes;
            $profile->user_id = $identity->id;

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


    public function afterSave($insert, $changedAttributes)
    {
        if ( $insert ) {
            $profile = new Profile();
            $profile->user_id = $this->id;
            $profile->save();
        }

        parent::afterSave($insert, $changedAttributes);
    }


    public function beforeDelete()
    {
        if ( parent::beforeDelete() ) {
            /**
             * @var $services Service[]
             */
            $services = $this->services;
            foreach ( $services as $service ) {
                $service->delete();
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

    public function addPoints($points)
    {
        if ( $points != 0 ) {
            return $this->updateCounters(['points' => $points]);
        }
        return true;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @param integer $timeout
     * @return static|null
     */
    public static function findByPasswordResetToken($token, $timeout)
    {
        if (!static::isPasswordResetTokenValid($token, $timeout)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }


    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @param integer $timeout
     * @return bool
     */
    public static function isPasswordResetTokenValid($token, $timeout)
    {
        if (empty($token)) {
            return false;
        }
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $timeout >= time();
    }


    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }


    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
