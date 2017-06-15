<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 29.05.17
 * Time: 17:43
 */

namespace app\extended\eauth;

use nodge\eauth\ErrorException;

class VKontakteOAuth2Service extends \nodge\eauth\services\VKontakteOAuth2Service
{

    const API_VERSION = '5.65';

    const SCOPE_FRIENDS = 'friends';
    const SCOPE_PHOTOS = 'photos';
    const SCOPE_EMAIL = 'email';
    const SCOPE_STATUS = 'status';
    const SCOPE_OFFLINE = 'offline';
    const SCOPE_VIDEO = 'video';
    const SCOPE_GROUPS = 'groups';
    const SCOPE_MARKET = 'market';

    protected $scopes = [self::SCOPE_EMAIL, self::SCOPE_PHOTOS, self::SCOPE_VIDEO, self::SCOPE_GROUPS, self::SCOPE_MARKET];


    protected function fetchAttributes()
    {
        $tokenData = $this->getAccessTokenData();

        $info = $this->makeSignedRequest('users.get', [
            'query' => [
                'user_ids' => $tokenData['params']['user_id'],
                'fields' => 'nickname, timezone, photo, photo_100',
                'v' => self::API_VERSION
            ],
        ]);

        if ( key_exists('error', $info) ) {
            throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
        }

        $info = $info['response'][0];

        $this->attributes = $info;
        $this->attributes['id'] = $info['id'];
        $this->attributes['name'] = $info['first_name'] . ' ' . $info['last_name'];
        $this->attributes['url'] = 'http://vk.com/id' . $info['id'];

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


    /**
     * @param $ids
     * @return mixed
     * @throws ErrorException
     */
    public function getPhotosById($ids)
    {
        $info = $this->makeSignedRequest('photos.getById', [
            'query' => [ 'photos' => is_array($ids) ? implode(',', $ids) : $ids, 'v' => self::API_VERSION ],
        ]);

        if ( key_exists('error', $info) ) {
            throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
        }

        return $info['response'];
    }


    public function getWallById($ids)
    {
        $info = $this->makeSignedRequest('wall.getById', [
            'query' => [ 'posts' => is_array($ids) ? implode(',', $ids) : $ids, 'v' => self::API_VERSION ],
        ]);

        if ( key_exists('error', $info) ) {
            throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
        }

        return $info['response'];
    }


    public function getVideosById($ids)
    {
        $info = $this->makeSignedRequest('video.get', [
            'query' => [ 'videos' => is_array($ids) ? implode(',', $ids) : $ids, 'v' => self::API_VERSION ],
        ]);

        if ( key_exists('error', $info) ) {
            throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
        }

        return $info['response'];
    }


    public function getProductById($ids, $extended = false)
    {
        $info = $this->makeSignedRequest('market.getById', [
            'query' => [ 'item_ids' => is_array($ids) ? implode(',', $ids) : $ids, 'extended' => $extended, 'v' => self::API_VERSION ],
        ]);

        if ( key_exists('error', $info) ) {
            throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
        }

        return $info['response'];
    }


    /**
     * @param $itemType
     * @param $ownerId
     * @param $itemId
     * @return mixed
     * @throws ErrorException
     */
    public function getIsLiked($itemType, $ownerId, $itemId)
    {
        switch ($itemType) {
            case 'product':

                $info = $this->getProductById($ownerId . '_' . $itemId, true);

                if ( key_exists('error', $info) ) {
                    throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
                }

                if ( $info['count'] == 0 ) {
                    return false;
                }

                return $info['items'][0]['likes']['user_likes'] == 1;

            default:
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
                    throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
                }

                return $info['response']['liked'] > 0;
        }
    }


    /**
     * @param $itemType
     * @param $ownerId
     * @param $itemId
     * @return mixed
     * @throws ErrorException
     */
    public function getIsReposted($itemType, $ownerId, $itemId)
    {
        switch ($itemType) {
            case 'product':

                return false;

//                $info = $this->getProductById($ownerId . '_' . $itemId, true);
//
//                if ( key_exists('error', $info) ) {
//                    throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
//                }
//
//                if ( $info['count'] == 0 ) {
//                    return false;
//                }
//
//                return $info['items'][0]['likes']['user_likes'] == 1;

            default:
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
                    throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
                }

                return $info['response']['copied'] > 0;
        }
    }


    public function getUsers($ids)
    {
        $info = $this->makeSignedRequest('users.get', [
            'query' => [
                'user_ids' => is_array($ids) ? implode(',', $ids) : $ids,
                'fields' => 'photo_200',
                'v' => self::API_VERSION
            ],
        ]);

        if ( key_exists('error', $info) ) {

            if ( $info['error']['error_code'] == 113 ) {
                return null;
            }

            throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
        }

        return $info['response'];
    }


    public function getGroups($ids)
    {
        $info = $this->makeSignedRequest('groups.getById', [
            'query' => [ 'group_ids' => is_array($ids) ? implode(',', $ids) : $ids, 'v' => self::API_VERSION ],
        ]);

        if ( key_exists('error', $info) ) {
            return null;
        }

        return $info['response'];
    }


    public function getIsMember($itemType, $itemId)
    {
        switch ($itemType) {
            case 'user':
                $subscriptions = $this->getUserSubscriptions();
                $items = $subscriptions['users']['items'];
                foreach ( $items as $item ) {
                    if ( $item == $itemId ) {
                        return true;
                    }
                }
                return false;

            case 'page':
            case 'group':
                return $this->getIsGroupMember($itemId);
        }

        return false;
    }


    public function getUserSubscriptions($id = null)
    {
        $query = [];
        if ( $id ) $query['user_id'] = $id;
        $query['v'] = self::API_VERSION;

        $info = $this->makeSignedRequest('users.getSubscriptions', [
            'query' => $query,
        ]);

        if ( key_exists('error', $info) ) {
            return null;
        }

        return $info['response'];
    }


    public function getIsGroupMember($groupId, $userId = null)
    {
        $query = [
            'group_id' => is_array($groupId) ? implode(',', $groupId) : $groupId
        ];
        if ( $userId ) $query['user_id'] = $userId;
        $query['v'] = self::API_VERSION;

        $info = $this->makeSignedRequest('groups.isMember', [
            'query' => $query,
        ]);

        if ( key_exists('error', $info) ) {
            return null;
        }

        return $info['response'] == 1;
    }


    public function getLastComment($itemType, $ownerId, $itemId)
    {
        switch ( $itemType ) {
            case 'photo':



                break;
        }

        return null;
    }

}