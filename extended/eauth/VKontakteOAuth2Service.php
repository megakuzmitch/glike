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


    const ITEM_TYPE_POST = 'post';
    const ITEM_TYPE_COMMENT = 'comment';
    const ITEM_TYPE_PHOTO = 'photo';
    const ITEM_TYPE_AUDIO = 'audio';
    const ITEM_TYPE_VIDEO = 'video';
    const ITEM_TYPE_NOTE = 'note';
    const ITEM_TYPE_MARKET = 'market';
    const ITEM_TYPE_USER = 'user';
    const ITEM_TYPE_GROUP = 'group';
    const ITEM_TYPE_PAGE = 'page';


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
     * @param $itemId
     * @param $ownerId
     * @return mixed
     * @throws ErrorException
     */
    public function getIsLiked($itemType, $itemId, $ownerId)
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
     * @param $itemId
     * @param $ownerId
     * @return mixed
     * @throws ErrorException
     */
    public function getIsReposted($itemType, $itemId, $ownerId)
    {
        switch ($itemType) {
            case 'market':

                $copiesList = $this->getLikesList($itemType, $itemId, $ownerId, 'copies');

                if ( $copiesList['count'] == 0 ) {
                    return false;
                }

                $items = $copiesList['items'];
                $userId = $this->getAttribute('id');
                foreach ( $items as $item ) {
                    if ( $userId == $item ) {
                        return true;
                    }
                }
                return false;

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


    /**
     * @param $itemType
     * @param $itemId
     * @param null $ownerId
     * @param string|null $filter values ("likes" | "copies")
     * @return
     * @throws ErrorException
     */
    public function getLikesList($itemType, $itemId, $ownerId = null, $filter = null)
    {
        $query = [
            'type' => $itemType,
            'item_id' => $itemId,
            'v' => self::API_VERSION
        ];

        if ( $ownerId ) $query['owner_id'] = $ownerId;
        if ( $filter ) $query['filter'] = $filter;

        $info = $this->makeSignedRequest('likes.getList', [
            'query' => $query,
        ]);

        if ( key_exists('error', $info) ) {

            throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
        }

        return $info['response'];
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


    public function getLastCommentFrom($itemType, $itemId, $ownerId, $fromId = null)
    {
        if ( $fromId === null ) {
            $fromId = $this->getAttribute('id');
        }
        $comments = [];
        switch ( $itemType ) {
            case 'photo':
                $comments = $this->getPhotosComments($itemId, $ownerId);
                break;
            case 'product':
                $comments = $this->getMarketComments($itemId, $ownerId);
                break;
            case 'wall':
                $comments = $this->getWallComments($itemId, $ownerId);
                break;
            case 'video':
                $comments = $this->getVideoComments($itemId, $ownerId);
                break;
        }

        if ( $comments['count'] > 0 ) {
            foreach ( $comments['items'] as $comment ) {
                if ( $comment['from_id'] == $fromId ) {
                    return $comment;
                }
            }
        }

        return null;
    }


    public function getPhotosComments($photoId, $ownerId = null)
    {
        $query = [
            'photo_id' => $photoId,
            'v' => self::API_VERSION
        ];
        if ( $ownerId ) $query['owner_id'] = $ownerId;

        $info = $this->makeSignedRequest('photos.getComments', [
            'query' => $query,
        ]);

        if ( key_exists('error', $info) ) {
            throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
        }

        return $info['response'];
    }


    public function getMarketComments($productId, $ownerId = null)
    {
        $query = [
            'item_id' => $productId,
            'v' => self::API_VERSION
        ];
        if ( $ownerId ) $query['owner_id'] = $ownerId;

        $info = $this->makeSignedRequest('market.getComments', [
            'query' => $query,
        ]);

        if ( key_exists('error', $info) ) {
            throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
        }

        return $info['response'];
    }


    public function getWallComments($postId, $ownerId = null)
    {
        $query = [
            'post_id' => $postId,
            'v' => self::API_VERSION
        ];
        if ( $ownerId ) $query['owner_id'] = $ownerId;

        $info = $this->makeSignedRequest('wall.getComments', [
            'query' => $query,
        ]);

        if ( key_exists('error', $info) ) {
            throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
        }

        return $info['response'];
    }


    public function getVideoComments($videoId, $ownerId = null)
    {
        $query = [
            'video_id' => $videoId,
            'v' => self::API_VERSION
        ];
        if ( $ownerId ) $query['owner_id'] = $ownerId;

        $info = $this->makeSignedRequest('video.getComments', [
            'query' => $query,
        ]);

        if ( key_exists('error', $info) ) {
            throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
        }

        return $info['response'];
    }
}