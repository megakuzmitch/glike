<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 21.06.17
 * Time: 10:51
 */

namespace app\widgets;


use nodge\eauth\Widget;
use Yii;

class EAuthWidget extends Widget
{
    public $serviceIdentities;


    public function init()
    {
        // EAuth component
        /** @var $component \nodge\eauth\EAuth */
        $component = Yii::$app->get($this->component);

        // Some default properties from component configuration
        if (!isset($this->services)) {
            $this->services = $component->getServices();
        }

        if (is_array($this->predefinedServices)) {
            $_services = [];
            foreach ($this->predefinedServices as $_serviceName) {
                foreach ( $this->services as $key => $_service ) {
                    if ( $_serviceName === $_service->id ) {
                        $_services[$key] = $_service;
                        break;
                    }
                }
            }
            $this->services = $_services;
        }

        $this->serviceIdentities = [];
        $eauth = Yii::$app->get('eauth');
        foreach ( $this->services as $name => $service ) {
            $this->serviceIdentities[$name] = $eauth->getIdentity($name);
        }

        if (!isset($this->popup)) {
            $this->popup = $component->popup;
        }

        // Set the current route, if it is not set.
        if (!isset($this->action)) {
            $this->action = '/' . Yii::$app->requestedRoute;
        }
    }

    /**
     * Executes the widget.
     * This method is called by {@link CBaseController::endWidget}.
     */
    public function run()
    {
        echo $this->render('eauth_widget', [
            'id' => $this->getId(),
            'services' => $this->services,
            'servicesIdentities' => $this->serviceIdentities,
            'action' => $this->action,
            'popup' => $this->popup,
            'assetBundle' => $this->assetBundle,
            'user' => $user = Yii::$app->user->getIdentity()
        ]);
    }
}