<?php
/**
 * Created by PhpStorm.
 * User: megakuzmitch
 * Date: 17.07.17
 * Time: 11:22
 */

namespace app\helpers;


class SiteHelper
{

    /**
     * @param $n
     * @param $forms
     * e.g. array('арбуз', 'арбуза', 'арбузов')
     * @return mixed
     */
    public static function plural($n, $forms) {
        return $n % 10 == 1 && $n % 100 != 11
            ? $forms[0]
            : ( $n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20 )
                ? $forms[1]
                : $forms[2] );
    }
}