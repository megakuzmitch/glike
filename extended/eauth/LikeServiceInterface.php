<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 08.06.17
 * Time: 11:24
 */

namespace app\extended\eauth;


interface LikeServiceInterface
{
    public function getPhotosById($ids);

    public function getIsLiked($type);
}