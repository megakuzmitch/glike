<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 21.07.17
 * Time: 12:20
 */

namespace app\auth;


use dektrium\user\clients\ClientInterface;
use dektrium\user\models\Account;
use yii\authclient\OAuth2;

class Instagram extends OAuth2 implements ClientInterface, AccountInterface
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://api.instagram.com/oauth/authorize';

    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.instagram.com/oauth/access_token';

    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.instagram.com/v1';

    /**
     * @inheritdoc
     */
    public $scope = 'basic';

    /**
     * Initializes authenticated user attributes.
     * @return array auth user attributes.
     */
    protected function initUserAttributes()
    {
        $response = $this->api('users/self');
        return $response['data'];
    }

    /** @return string|null User's email */
    public function getEmail()
    {
        // TODO: Implement getEmail() method.
    }

    /** @return string|null User's username */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function getImageUrl(Account $account)
    {
        // TODO: Implement getImageUrl() method.
    }

    public function getDisplayName(Account $account)
    {
        // TODO: Implement getDisplayName() method.
    }
}