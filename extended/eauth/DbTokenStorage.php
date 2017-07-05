<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 22.06.17
 * Time: 14:40
 */

namespace app\extended\eauth;

use OAuth\Common\Storage\Exception\TokenNotFoundException;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Token\TokenInterface;
use yii\base\Component;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;


/**
 * Class DbTokenStorage
 * @package app\extended\eauth
 * @var $db Connection
 */
class DbTokenStorage extends Component implements TokenStorageInterface
{
    /** @var Connection $db */
    public $db = 'db';
    public $tokenTable = '{{token_storage}}';
    public $stateTable = '{{state_storage}}';

    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
    }

    /**
     * @param string $service
     * @return TokenInterface
     * @throws TokenNotFoundException
     */
    public function retrieveAccessToken($service)
    {
        if ( $this->hasAccessToken($service) ) {
            $query = new Query();
            /** @var $token TokenInterface */
            $token = $query->from($this->tokenTable)
                ->select('token')
                ->where('[[service]]=:service', [':service' => $service])
                ->scalar($this->db);
            return $token === false ? null : $token;
        }
        throw new TokenNotFoundException('Token not found in database, are you sure you stored it?');
    }

    /**
     * @param string $service
     * @param TokenInterface $token
     * @return TokenStorageInterface
     */
    public function storeAccessToken($service, TokenInterface $token)
    {
        if ( $this->hasAccessToken($service) ) {
            $this->db->createCommand()
                ->update($this->sessionTable, ['token' => $token],
                    '[[service]]=:service', [':service' => $service])
                ->execute();
        } else {
            $this->db->createCommand()
                ->insert($this->tokenTable, ['service' => $service, 'token' => $token])
                ->execute();
        }

        return $this;
    }

    /**
     * @param string $service
     * @return bool
     */
    public function hasAccessToken($service)
    {
        $query = new Query;
        $exists = $query->select(['service'])
            ->from($this->tokenTable)
            ->where('[[service]]=:service', [':service' => $service])
            ->scalar($this->db);

        return $exists !== false;
    }

    /**
     * Delete the users token. Aka, log out.
     * @param string $service
     * @return TokenStorageInterface
     */
    public function clearToken($service)
    {
        $this->db->createCommand()
            ->delete($this->tokenTable,
                '[[service]]=:service', [':service' => $service])
            ->execute();
        return $this;
    }

    /**
     * Delete *ALL* user tokens. Use with care. Most of the time you will likely
     * want to use clearToken() instead.
     * @return TokenStorageInterface
     */
    public function clearAllTokens()
    {
        $this->db->createCommand()->delete($this->tokenTable)->execute();
        return $this;
    }

    /**
     * Store the authorization state related to a given service
     *
     * @param string $service
     * @param string $state
     * @return TokenStorageInterface
     */
    public function storeAuthorizationState($service, $state)
    {
        if ( $this->hasAuthorizationState($service) ) {
            $this->db->createCommand()
                ->update($this->stateTable,
                    ['state' => $state],
                    'service=:service', [':service' => $service])
                ->execute();
        } else {
            $this->db->createCommand()
                ->insert($this->stateTable, ['service' => $service, 'state' => $state])
                ->execute();
        }

        return $this;
    }

    /**
     * Check if an authorization state for a given service exists
     * @param string $service
     * @return bool
     */
    public function hasAuthorizationState($service)
    {
        $query = new Query;
        $exists = $query->select(['service'])
            ->from($this->stateTable)
            ->where('[[service]]=:service', [':service' => $service])
            ->scalar($this->db);

        return $exists !== false;
    }

    /**
     * Retrieve the authorization state for a given service
     * @param string $service
     * @return string
     */
    public function retrieveAuthorizationState($service)
    {
        if ( $this->hasAuthorizationState($service) ) {
            $query = new Query();
            $state = $query->from($this->stateTable)
                ->select('state')
                ->where('[[service]]=:service', [':service' => $service])
                ->scalar($this->db);
            return $state === false ? null : $state;
        }
        return null;
    }

    /**
     * Clear the authorization state of a given service
     * @param string $service
     * @return TokenStorageInterface
     */
    public function clearAuthorizationState($service)
    {
        $this->db->createCommand()
            ->delete($this->stateTable,
                '[[service]]=:service', [':service' => $service])
            ->execute();
        return $this;
    }

    /**
     * Delete *ALL* user authorization states. Use with care. Most of the time you will likely
     * want to use clearAuthorization() instead.
     * @return TokenStorageInterface
     */
    public function clearAllAuthorizationStates()
    {
        $this->db->createCommand()->delete($this->stateTable)->execute();
        return $this;
    }
}