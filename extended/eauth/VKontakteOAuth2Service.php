<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 29.05.17
 * Time: 17:43
 */

namespace app\extended\eauth;


class VKontakteOAuth2Service extends \nodge\eauth\services\VKontakteOAuth2Service
{

    const API_VERSION = '5.64';

    const SCOPE_FRIENDS = 'friends';
    const SCOPE_EMAIL = 'email';
    const SCOPE_STATUS = 'status';
    const SCOPE_OFFLINE = 'status';

    protected $scopes = [self::SCOPE_EMAIL];

    protected function fetchAttributes()
    {
        $tokenData = $this->getAccessTokenData();

        $info = $this->makeSignedRequest('users.get', [
            'query' => [
                'uids' => $tokenData['params']['user_id'],
                'fields' => 'nickname, timezone, photo, photo_100',
            ],
        ]);

        $info = $info['response'][0];

        $this->attributes = $info;
        $this->attributes['id'] = $info['uid'];
        $this->attributes['name'] = $info['first_name'] . ' ' . $info['last_name'];
        $this->attributes['url'] = 'http://vk.com/id' . $info['uid'];

        $this->attributes['email'] = empty($tokenData['params']['email']) ? "" : $tokenData['params']['email'];

//        if (!empty($info['nickname'])) {
//            $this->attributes['username'] = $info['nickname'];
//        } else {
//            $this->attributes['username'] = 'id' . $info['uid'];
//        }

        if (!empty($info['timezone'])) {
            $this->attributes['timezone'] = timezone_name_from_abbr('', $info['timezone'] * 3600, date('I'));
        }
        if (!empty($info['photo_100'])) {
            $this->attributes['avatar'] = $info['photo_100'];
        }

        return true;
    }

}