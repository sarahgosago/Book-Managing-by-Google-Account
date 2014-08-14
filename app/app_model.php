<?php

class AppModel extends Model
{
    public static function getConnection()
    {
        $db = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }

    public static function getTKConnection()
    {
        $db = new PDO(TK_DB_DSN, TK_DB_USERNAME, TK_DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }
}
