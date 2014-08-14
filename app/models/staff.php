<?php

class Staff extends AppModel
{
    const TABLE_NAME = 'staffs';

    public static function validateEmail($username)
    {
        return self::getUserInfoByEmail($username) ? true : false;
    }

    public static function getUserInfoByEmail($username)
    {
        $con = DB::TKConn();
        $sql = "SELECT * FROM " . self::TABLE_NAME . " WHERE username = ?";
        $row = $con->row($sql, array($username));
        return $row ? new self($row) : '';
    }

    public static function getAll()
    {
        $con = DB::TKConn();
        $sql = "SELECT * FROM " . self::TABLE_NAME . "";
        $staff = array();
        foreach($con->rows($sql) as $row) {
            $staff[] = new self($row);
        }
        return $staff;
    }
}
