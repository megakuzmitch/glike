<?php


use rmrevin\yii\fontawesome\FA;
use rmrevin\yii\fontawesome\FontAwesome;
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
        <? foreach ($services as $name => $service) {
            echo '<li class="eauth-service eauth-service-id-' . $service->id . '">';

            if ( $servicesIdentities[$name]->getIsAuthenticated() ) {
                $attributes = $servicesIdentities[$name]->getAttributes();
                echo '<div class="eauth-service-linked">';
                    echo '<b>' . $service->title . '</b>';
                    echo Html::img($attributes['avatar'], ['class' => 'img-circle']);
                    echo '<div class="eauth-service-info">';
                        echo Html::tag('div', $attributes['name'], ['class' => 'name']);
                        echo Html::a('Отвязать аккаунт', ['/user/social/remove', 'service' => $name], [
                            'class' => 'eauth-service-logout btn btn-default btn-xs',
                            'data-eauth-service' => $service->id,
                        ]);
                    echo '</div>';
                echo '<div>';
            } else {
                echo Html::a('<span class="icon"></span>' . $service->title, [$action, 'service' => $name], [
                    'class' => 'eauth-service-link btn btn-block btn-lg',
                    'data-eauth-service' => $service->id,
                ]);
            }

            echo '</li>';
        } ?>
    </ul>
</div>
