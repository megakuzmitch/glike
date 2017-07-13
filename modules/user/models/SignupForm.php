<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 08.05.2017
 * Time: 21:27
 */

namespace app\modules\user\models;


use Yii;
use yii\base\Model;

/**
 * Class SignupForm
 * @package app\modules\user\models
 */
class SignupForm extends Model
{
//    public $username;
    public $email;
    public $password;
    public $confirm_password;
//    public $verifyCode;

    private $_loaded = false;

    public function rules()
    {
        return [
//            ['username', 'filter', 'filter' => 'trim'],
//            ['username', 'required'],
//            ['username', 'match', 'pattern' => '#^[\w_-]+$#i'],
//            ['username', 'unique', 'targetClass' => User::className(), 'message' => 'This username has already been taken.'],
//            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'Пользователь с таким email уже зарегестрирован'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['confirm_password', 'required'],
            ['confirm_password', 'confirmPassword'],

//            ['verifyCode', 'captcha', 'captchaAction' => '/user/default/captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'password' => 'Пароль',
            'confirm_password' => 'Подтверждение пароля',
        ];
    }


    public function confirmPassword($attribute, $params)
    {
        if ( $this->{$attribute} !== $this->password ) {
            $this->addError($attribute, 'Пароли не совпадают');
        }
    }


    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ( $this->validate() ) {
            $user = new User();
            $user->username = $this->email;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generateEmailConfirmToken();

            if ( YII_DEBUG ) {
                $user->points = 1000;
            }

            if ( $user->save() ) {
                Yii::$app->mailer->compose('@app/modules/user/mails/thanks', ['model' => $this])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Регистрация в сервисе ' . Yii::$app->name)
                    ->send();

                Yii::$app->mailer->compose('@app/modules/user/mails/emailConfirm', ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Email confirmation for ' . Yii::$app->name)
                    ->send();
                return $user;
            }
        }

        return null;
    }


    public function load($data, $formName = null)
    {
        $this->_loaded = parent::load($data, $formName);
        return $this->_loaded;
    }


    public function getIsLoad()
    {
        return $this->_loaded;
    }
}