<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 20.06.17
 * Time: 14:33
 */

namespace app\modules\user\models;


use Yii;
use yii\base\Model;

/**
 * Class TaskFilter
 * @package app\modules\user\models
 * @property $service_type integer
 * @property $task_type integer
 */
class TaskFilter extends Model
{
    public $service_type;
    public $task_type;


    public function __construct(array $config = [])
    {
        $this->service_type = Task::SERVICE_TYPE_VK;
        parent::__construct($config);
    }


    public function rules()
    {
        return [
            [['service_type', 'task_type'], 'integer']
        ];
    }


    public static function getServiceTypes()
    {
        return [
            Task::SERVICE_TYPE_VK => 'В контакте',
        ];
    }


    public function search()
    {
        $query = Task::find();

        if ( $this->load(Yii::$app->request->get()) ) {
            if ( $this->service_type ) {
                Yii::$app->session->set('currentService', $this->service_type);
                $service = Yii::$app->get('eauth')->getIdentity(Task::getServiceType($this->service_type));
                $user = Yii::$app->user->identity;
                if ( $user ) {
                    $serviceId = $user->getServices($service->getServiceName())->select('identity_id')->scalar();
                    if ( $serviceId ) {
                        $user->setCurrentProfile($serviceId);
                    }
                }
            }
        } else {
            $this->service_type = Yii::$app->session->get('currentService');
        }

        if ( $this->service_type ) {
            $query->andWhere(['service_type' => $this->service_type]);
        }

        if ( $this->task_type ) {
            $query->andWhere(['task_type' => $this->task_type]);
        }

        return $query;
    }
}