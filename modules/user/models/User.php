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
class User extends \dektrium\user\models\User implements IdentityInterface
{
//    const STATUS_ACTIVE = 1;
//    const STATUS_WAIT = 2;
//    const STATUS_BLOCKED = 3;

//    protected $_profile;
//
//    public static function tableName()
//    {
//        return '{{%user}}';
//    }
//
//    public function getServices($serviceName = false, $identityId = false)
//    {
//        $query = $this->hasMany(Service::className(), ['user_id' => 'id']);
//        if ( $serviceName ) {
//            $query->where(['service_name' => $serviceName]);
//        }
//        if ( $identityId ) {
//            $query->andWhere(['identity_id' => $identityId]);
//        }
//        return $query;
//    }
//
//    public function getProfile($type = null) {
//        return $this->hasOne(Profile::className(), ['user_id' => 'id'])
//            ->where(['type' => $type]);
//    }


    public function getCurrentProfile()
    {
//        return $this->getProfile(Yii::$app->session->get('currentProfile'))->one();
        return $this->profile;
    }

    public function setCurrentProfile($type)
    {
        Yii::$app->session->set('currentProfile', $type);
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
     * @param bool $force
     * @return Service
     * @throws Exception
     */
    public function linkService($socialService, $force = true)
    {
        if ( $serviceRecord = $this->getServices($socialService->getServiceName(), $socialService->getId())->one() ) {
            return $serviceRecord;
        }

        if ( $serviceRecord = $this->getServices($socialService->getServiceName())->one() ) {
            if ( $force ) {
                $serviceRecord->identity_id = $socialService->getId();
                $serviceRecord->save();
                return $serviceRecord;
            } else {
                throw new Exception('Вы уже авторизовывались ранее в этой социальной сети под другим именем');
            }
        }

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
//        if ( !$this->getServices())
//
//        foreach ( $services as $service ) {
//            if ( $serviceName == $service->service_name ) {
//                return $service->identity_id;
//            }
//        }

        return null;
    }



    public static function getUserByEAuth(\nodge\eauth\ServiceBase $service)
    {
        if ( !$service->getIsAuthenticated() ) {
            throw new ErrorException('EAuth user should be authenticated before creating identity.');
        }

        $email = $service->getAttribute('email');
        $profileAttributes = [
            'first_name' => $service->getAttribute('first_name'),
            'last_name' => $service->getAttribute('last_name'),
            'avatar' => $service->getAttribute('avatar'),
        ];

        $user = self::findByEmail($email);

        if ( !$user ) {
            $signupForm = new SignupForm();
            $signupForm->email = $email;
            $signupForm->password = Yii::$app->security->generateRandomString(8);
            $signupForm->confirm_password = $signupForm->password;
            $user = $signupForm->signup();
        }

        if ( $user ) {
            $user->linkService($service);
            $user->updateProfile($service->id, $profileAttributes);
        } else {
            throw new \yii\db\Exception('Ошибка при создании пользователя');
        }

        return $user;
    }


    /**
     * @param null $type
     * @param $attributes
     * @return bool|false|int
     */
    public function updateProfile($type = null, $attributes)
    {
        if ( !is_array($attributes) || $this->isNewRecord ) {
            return false;
        }

        /** @var $profile ActiveRecord */
        $profile = $this->getProfile($type)->one();

        if ( $profile === null ) {
            $profile = new Profile($type);
            $profile->user_id = $this->id;
        }
        $profile->attributes = $attributes;
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
