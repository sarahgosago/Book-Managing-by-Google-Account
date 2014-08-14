<?php

class Admin extends AppModel
{
    const TABLE_NAME = 'admin';

    public static function checkIfAdmin($username)
    {
        $con = DB::conn();
        $sql = "SELECT username FROM " . self::TABLE_NAME . " WHERE username = ?";
        return $con->row($sql, array($username));
    }

    public static function getAllAdmins()
    {
        $con = DB::conn();
        $sql = "SELECT * FROM " . self::TABLE_NAME . "";
        return $con->rows($sql);
    }

    public static function addAdmin($username, $firstname, $lastname)
    {
        $con = DB::conn();
        $con->insert(self::TABLE_NAME, array("username" => $username, "firstname" => $firstname, "lastname" => $lastname));
        return $con->rowCount();
    }

    public static function deleteAdmin($id)
    {
        $con = DB::conn();
        $sql = "DELETE FROM " . self::TABLE_NAME . " WHERE id = ?";
        $con->query($sql, array($id));
        return $con->rowCount();
    }
}