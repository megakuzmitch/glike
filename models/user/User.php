<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 18.07.17
 * Time: 12:56
 */

namespace app\models\user;

use Yii;
use yii\helpers\Json;

class User extends \dektrium\user\models\User
{
    private $_currentAccountData;

    public function setCurrentAccountProvider($provider)
    {
        $this->_currentAccountData = null;
        Yii::$app->session->set('_current_account_provider', $provider);
    }


    public function getCurrentAccountData()
    {
        if ( $this->_currentAccountData !== null ) {
            return $this->_currentAccountData;
        }

        $provider = Yii::$app->session->get('_current_account_provider');
        if ( ! ($account = $this->getAccountByProvider($provider)) ) {
            return null;
        }
        $accountData = $account->getDecodedData();
        $this->_currentAccountData = [];
        switch ( $provider ) {
            case 'google':
                $this->_currentAccountData['name'] = $accountData['displayName'];
                $this->_currentAccountData['image'] = $accountData['image']['url'];
                break;
        }

        return $this->_currentAccountData;
    }


    public function getCurrentDisplayName()
    {
        $accountData = $this->getCurrentAccountData();
        return $accountData['name'];
    }


    public function getCurrentImageUrl()
    {
        $accountData = $this->getCurrentAccountData();
        return $accountData['image'];
    }
}