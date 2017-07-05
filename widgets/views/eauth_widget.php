<?php

use yii\bootstrap\Html;
use yii\web\View;

/** @var $this View */
/** @var $id string */
/** @var $services stdClass[] See EAuth::getServices() */
/** @var $action string */
/** @var $popup bool */
/** @var $assetBundle string Alias to AssetBundle */
/** @var $user \app\modules\user\models\User */

Yii::createObject(['class' => $assetBundle])->register($this);

// Open the authorization dilalog in popup window.
if ($popup) {
    $options = [];
    foreach ($services as $name => $service) {
        $options[$service->id] = $service->jsArguments;
    }
    $this->registerJs('$("#' . $id . '").eauth(' . json_encode($options) . ');');
}

?>
<div class="eauth eauth-widget" id="<?php echo $id; ?>">
    <ul class="eauth-list">
        <?php
        foreach ($services as $name => $service) {

            echo '<li class="eauth-service eauth-service-id-' . $service->id . '">';

            /**
             * @var $eauthService \nodge\eauth\oauth2\Service
             */
            $eauthService = Yii::$app->eauth->getIdentity($name);
            if ( $user->getIsLinked($eauthService) ) {
//                $attributes = $eauthService->getAttributes();

//                echo Html::img($eauthService->getAttribute('avatar'));
//                echo Html::tag('span', $eauthService->getAttribute('name'));
//                echo Html::a('Отвязать аккаунт', ['/user/default/unlink', 'service' => $name], [
//                    'class' => 'eauth-service-unlink',
//                    'data-eauth-service' => $service->id,
//                ]);

            } else {
                echo Html::a($service->title, [$action, 'service' => $name], [
                    'class' => 'eauth-service-link',
                    'data-eauth-service' => $service->id,
                ]);
            }

            echo '</li>';
        }
        ?>
    </ul>
</div>
