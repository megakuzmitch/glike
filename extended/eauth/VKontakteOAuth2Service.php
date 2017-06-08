<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 29.05.17
 * Time: 17:43
 */

namespace app\extended\eauth;


use OAuth\Common\Exception\Exception;

class VKontakteOAuth2Service extends \nodge\eauth\services\VKontakteOAuth2Service
{

    const API_VERSION = '5.65';

    const SCOPE_FRIENDS = 'friends';
    const SCOPE_EMAIL = 'email';
    const SCOPE_STATUS = 'status';
    const SCOPE_OFFLINE = 'status';
    const SCOPE_VIDEO = 'video';

    protected $scopes = [self::SCOPE_EMAIL, self::SCOPE_VIDEO];

    protected function fetchAttributes()
    {
        $tokenData = $this->getAccessTokenData();

        $info = $this->makeSignedRequest('users.get', [
            'query' => [
                'uids' => $tokenData['params']['user_id'],
                'fields' => 'nickname, timezone, photo, photo_100',
            ],
        ]);

        if ( key_exists('error', $info) ) {
            throw new Exception($info['error']['error_msg'], $info['error']['error_code']);
        }

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


    public function getPhotosById($ids)
    {
        $info = $this->makeSignedRequest('photos.getById', [
            'query' => [ 'photos' => is_array($ids) ? implode(',', $ids) : $ids, 'v' => self::API_VERSION ],
        ]);

        if ( key_exists('error', $info) ) {
            throw new Exception($info['error']['error_msg'], $info['error']['error_code']);
        }

        return $info['response'];
    }


    public function getWallById($ids)
    {
        $info = $this->makeSignedRequest('wall.getById', [
            'query' => [ 'posts' => is_array($ids) ? implode(',', $ids) : $ids, 'v' => self::API_VERSION ],
        ]);

        if ( key_exists('error', $info) ) {
            throw new Exception($info['error']['error_msg'], $info['error']['error_code']);
        }

        return $info['response'];
    }


    public function getVideosById($ids)
    {
        $info = $this->makeSignedRequest('video.get', [
            'query' => [ 'videos' => is_array($ids) ? implode(',', $ids) : $ids, 'v' => self::API_VERSION ],
        ]);

        if ( key_exists('error', $info) ) {
            throw new Exception($info['error']['error_msg'], $info['error']['error_code']);
        }

        return $info['response'];
    }


    /**
     * @param $itemType
     * @param $ownerId
     * @param $itemId
     * @return mixed
     * @throws Exception
     */
    public function getIsLiked($itemType, $ownerId, $itemId)
    {
        $userAttributes = $this->getAttributes();

        $info = $this->makeSignedRequest('likes.isLiked', [
            'query' => [
                'user_id' => $userAttributes['id'],
                'type' => $itemType,
                'owner_id' => $ownerId,
                'item_id' => $itemId,
                'v' => self::API_VERSION

            ],
        ]);

        if ( key_exists('error', $info) ) {
            throw new Exception($info['error']['error_msg'], $info['error']['error_code']);
        }

        return $info['response'];
    }

}