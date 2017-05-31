<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 29.05.17
 * Time: 12:57
 */


use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\helpers\Url;


?>

<div class="navbar-header">
            <a class="navbar-brand" href="<?= Url::home() ?>">
                <?= Html::img('/img/Logo-G.png', [
    'alt' => 'GLike'
]) ?><span>Like</span>
    </a>
    </div>

<?php echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => array_filter([
        ['label' => 'Помощь', 'url' => ['/main/page/view', 'pageName' => 'help']],
        ['label' => 'О нас', 'url' => ['/main/page/view', 'pageName' => 'about']]
    ]),
]); ?>

