<?php

function isset_get(&$variable, $return = null)
{
    if (isset($variable)) {
        return $variable;
    }

    unset($variable);
    return $return;
}