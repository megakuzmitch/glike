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
    /**
     * Executes the widget.
     * This method is called by {@link CBaseController::endWidget}.
     */
    public function run()
    {
        $services = $this->services;

        foreach ( $services as $service ) {
        }

        echo $this->render('eauth_widget', [
            'id' => $this->getId(),
            'services' => $this->services,
            'action' => $this->action,
            'popup' => $this->popup,
            'assetBundle' => $this->assetBundle,
            'user' => $user = Yii::$app->user->getIdentity()
        ]);
    }
}