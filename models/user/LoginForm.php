<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 21.07.17
 * Time: 15:13
 */

namespace app\models\user;

use \dektrium\user\models\LoginForm as BaseLoginForm;
use Yii;

class LoginForm extends BaseLoginForm
{
    public function attributeLabels()
    {
        return [
            'login'      => Yii::t('user', 'Email'),
            'password'   => Yii::t('user', 'Password'),
            'rememberMe' => Yii::t('user', 'Remember me next time'),
        ];
    }
}