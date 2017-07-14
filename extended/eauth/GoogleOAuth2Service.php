<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 19.06.17
 * Time: 11:11
 */

namespace app\extended\eauth;

use yii\base\ErrorException;

class GoogleOAuth2Service extends \nodge\eauth\services\GoogleOAuth2Service
{
    protected $scopes = [self::SCOPE_EMAIL, self::SCOPE_USERINFO_EMAIL, self::SCOPE_USERINFO_PROFILE, self::SCOPE_YOUTUBE];


    public function remove()
    {
        $tokenStorage = $this->getTokenStorage();
        var_dump($tokenStorage); die();
    }


    protected function fetchAttributes()
    {
        $info = $this->makeSignedRequest('https://www.googleapis.com/oauth2/v2/userinfo', [
            'query' => [
                'fields' => 'id,email,given_name,family_name,name,picture,link'
            ]
        ]);

        if ( key_exists('error', $info) ) {
            throw new ErrorException($info['error']['error_msg'], $info['error']['error_code']);
        }

        $this->attributes['id'] = $info['id'];
        $this->attributes['first_name'] = $info['given_name'];
        $this->attributes['last_name'] = $info['family_name'];
        $this->attributes['name'] = $info['name'];

        if (!empty($info['email'])) {
            $this->attributes['email'] = $info['email'];
        }

        if (!empty($info['link'])) {
            $this->attributes['url'] = $info['link'];
        }

        if (!empty($info['picture']))
            $this->attributes['avatar'] = $info['picture'];
    }


    public function getVideos($ids)
    {
        if ( is_array($ids) ) {
            $ids = implode(',', $ids);
        }

        $query = [
            'id' => $ids,
            'part' => 'snippet'
        ];
        $info = $this->makeSignedRequest('https://www.googleapis.com/youtube/v3/videos', [
            'query' => $query
        ]);

        return $info;
    }


    public function getRating($ids)
    {
        if ( is_array($ids) ) {
            $ids = implode(',', $ids);
        }

        $query = [
            'id' => $ids
        ];
        $info = $this->makeSignedRequest('https://www.googleapis.com/youtube/v3/videos/getRating', [
            'query' => $query
        ]);

        return $info;
    }


    public function getComments($id)
    {
        $query = [
            'videoId' => $id
        ];
        $info = $this->makeSignedRequest('https://www.googleapis.com/youtube/v3/commentThreads', [
            'query' => $query
        ]);

        return $info;
    }


    public function getIsLiked($id)
    {
        $data = $this->getRating($id);
        if ( isset($data['items']) ) {
            return $data['items'][0]['rating'] == 'like';
        }

        return false;
    }

}