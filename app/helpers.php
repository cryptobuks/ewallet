<?php

/**
 * @param $name
 * @param $value
 * @param int $time
 * @param string $path
 * @param bool $secure
 */
function cookieSet($name, $value, $time = 0, $path = '/', $secure = false)
{
    setcookie(
        $name,
        $value,
        !$time ? time()+60*60*24*365 : $time,
        $path,
        ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false,
        $secure
    );
}
