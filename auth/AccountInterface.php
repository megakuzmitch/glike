<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 19.07.17
 * Time: 16:54
 */

namespace app\auth;


use dektrium\user\models\Account;

interface AccountInterface
{
    public function getImageUrl(Account $account);
    public function getDisplayName(Account $account);
}