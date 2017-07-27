<?php

/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 19.07.17
 * Time: 14:09
 */

namespace app\widgets\user;

use dektrium\user\clients\ClientInterface;
use yii\helpers\Html;


class Connect extends \dektrium\user\widgets\Connect
{

    /**
     * @param \yii\authclient\ClientInterface $client
     * @param null $text
     * @param array $htmlOptions
     * @return string|void
     */
    public function clientLink($client, $text = null, array $htmlOptions = [])
    {
        if ( $this->isConnected($client) ) {
            return $this->renderConnectedItem($client);
        } else {
            $text = Html::tag('span', '', ['class' => 'auth-icon ' . $client->getName()]);
            $text .= $client->getTitle();
            return parent::clientLink($client, $text);
        }
    }


    protected function getAccount(ClientInterface $client)
    {
        return $this->isConnected($client) ? $this->accounts[$client->getId()] : null;
    }


    /**
     * @param ClientInterface $client
     * @return string
     */
    public function renderConnectedItem($client)
    {
        $account = $this->getAccount($client);
        $imageUrl = $client->getImageUrl($account);
        $displayName = $client->getDisplayName($account);

        return Html::tag('div',
            Html::tag('b', $client->getTitle()) .
            Html::img($imageUrl, ['class' => 'img-circle']) .
            Html::tag('div',
                Html::tag('div', $displayName, ['class' => 'name']) .
                Html::a('Отвязать аккаунт', $this->createClientUrl($client), [
                    'class' => 'auth-client-disconnect btn btn-default btn-xs',
                    'data-method' => 'post'
                ]), ['class' => 'auth-account-info']), ['class' => 'auth-client-linked']);

//        echo '<div class="eauth-service-linked">';
//        echo '<b>' . $service->title . '</b>';
//        echo Html::img($attributes['avatar'], ['class' => 'img-circle']);
//        echo '<div class="eauth-service-info">';
//        echo Html::tag('div', $attributes['name'], ['class' => 'name']);
//        echo Html::a('Отвязать аккаунт', ['/user/social/remove', 'service' => $name], [
//            'class' => 'eauth-service-logout btn btn-default btn-xs',
//            'data-eauth-service' => $service->id,
//        ]);
//        echo '</div>';
//        echo '<div>';
    }
}