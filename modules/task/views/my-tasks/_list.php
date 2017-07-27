<?

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $taskFilter TaskFilter
 * @var $listOptions array
 */

use app\modules\user\models\TaskFilter;
use yii\widgets\ListView;

?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_list_item',
    'options' => ['id' => 'TaskList', 'class' => 'my-tasks-list list-view']
]) ?>