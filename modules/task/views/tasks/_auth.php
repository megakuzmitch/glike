<?php
use app\widgets\EAuthWidget;
?>

<div class="row centered">' .
    <div class="col-lg-4">
        <?= EAuthWidget::widget([
            'action' => '/user/social/auth',
            'predefinedServices' => [$service->getServiceName()]
        ]); ?>
    </div>
</div>