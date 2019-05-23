<?php

function isset_get(&$variable, $return = null)
{
    if (isset($variable)) {
        return $variable;
    }

    unset($variable);
    return $return;
}

function set_error($message, $code = 400)
{
    http_response_code($code);
    die(json_encode(["error" => $message, "code" => $code]));
}

function set_response($response)
{
    http_response_code(200);
    die(json_encode(compact('response')));
}