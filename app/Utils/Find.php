<?php

namespace App\Util;

function find($array, $func)
{
    foreach ($array as $item) {
        if ($func($item)) {
            return $item;
        }
    }

    return null;
}