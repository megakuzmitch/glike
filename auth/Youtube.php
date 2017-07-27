<?php
namespace app\auth;

use dektrium\user\clients\Google;
use dektrium\user\models\Account;

/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 18.07.17
 * Time: 12:38
 */
class Youtube extends Google implements AccountInterface
{

    public function getTitle()
    {
        return 'Youtube';
    }

    public function getUsername()
    {
        $email = $this->getEmail();
        if ( null === $email ) {
            return null;
        }
        $parts = explode('@', $email);
        return $parts[0];
    }

    public function getImageUrl(Account $account)
    {
        $data = $account->getDecodedData();
        return $data ? $data['image']['url'] : null;
    }

    public function getDisplayName(Account $account)
    {
        $data = $account->getDecodedData();
        return $data ? $data['displayName'] : null;
    }
}