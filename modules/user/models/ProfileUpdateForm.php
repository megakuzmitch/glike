<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 09.05.2017
 * Time: 0:12
 */

namespace app\modules\user\models;


use Yii;
use yii\base\Model;

class ProfileUpdateForm extends Model
{
    public $email;

    /**
     * @var User
     */
    private $_user;

    public function __construct(User $user, $config = [])
    {
        $this->_user = $user;
        $this->email = $user->email;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass' => User::className(),
                'message' => 'Такой email уже существует',
                'filter' => ['<>', 'id', $this->_user->id],
            ],
            ['email', 'string', 'max' => 255],
        ];
    }

    public function update()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->email = $this->email;
            return $user->save();
        } else {
            return false;
        }
    }
}