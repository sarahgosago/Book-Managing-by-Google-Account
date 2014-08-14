<?php
class MySession
{
    public static function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
         return false;
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function out($key)
    {
        unset($_SESSION[$key]);
    }
}
