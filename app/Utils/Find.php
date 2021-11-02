<?php

namespace App\Utils;

function Find($array, $func)
{
    foreach ($array as $item) {
        if ($func($item)) {
            return $item;
        }
    }

    return null;
}