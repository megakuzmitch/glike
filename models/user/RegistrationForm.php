<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 19.07.17
 * Time: 12:11
 */

namespace app\models\user;


use Yii;

class RegistrationForm extends \dektrium\user\models\RegistrationForm
{
    public function beforeValidate()
    {
        if ( empty($this->username) && !empty($this->email) ) {
            $parts = explode('@', $this->email);
            $this->username = $parts[0];
        }
    }


    public function rules()
    {
        $user = $this->module->modelMap['User'];

        return [
            // email rules
            'emailTrim'     => ['email', 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern'  => ['email', 'email'],
            'emailUnique'   => [
                'email',
                'unique',
                'targetClass' => $user,
                'message' => Yii::t('user', 'This email address has already been taken')
            ],
            // password rules
            'passwordRequired' => ['password', 'required', 'skipOnEmpty' => $this->module->enableGeneratingPassword],
            'passwordLength'   => ['password', 'string', 'min' => 6, 'max' => 72],
        ];
    }
}