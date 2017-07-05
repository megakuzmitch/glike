<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 30.06.17
 * Time: 12:54
 */

namespace app\widgets;


use yii\helpers\Html;

class ActiveField extends \yii\bootstrap\ActiveField
{
    public function radioList($items, $options = [])
    {
        Html::addCssClass($options, 'custom-radio-list');
        if ( !isset($options['item']) ) {
            $options['item'] = function($index, $label, $name, $checked, $value) use ($options) {
                $id = Html::getInputId($this->model, $this->attribute) . '_' . $index;
                $inputOptions = ['id' => $id, 'checked' => $checked];
                if ( array_key_exists('disabled', $options) && $options['disabled'] ) {
                    $inputOptions['disabled'] = true;
                }
                return Html::tag('div', Html::input('radio', $name, $value, $inputOptions) .
                    Html::label($label, $id), ['class' => 'custom-radio inline']);
            };
        }

        return parent::radioList($items, $options);
    }
}