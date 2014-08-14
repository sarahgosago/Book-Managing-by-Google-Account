<?php

class Comment extends AppModel
{
    const TABLE_NAME = 'comments';

    public static function insertComment($book_id, $user_id, $comment)
    {
        $con = DB::conn();
        $con->insert(self::TABLE_NAME, array("book_id" => $book_id, "staff_id" => $user_id, "comment" => $comment));
        return $con->lastInsertId();
    }

    public static function getByBookId($book_id)
    {
        $con = DB::conn();
        $sql = "SELECT * FROM " . self::TABLE_NAME . " WHERE book_id = ? ORDER BY comment_id DESC LIMIT 20";
        return $con->rows($sql, array($book_id));
    }

    public static function deleteById($comment_id)
    {
        $con = DB::conn();
        $sql = "DELETE FROM " . self::TABLE_NAME . " WHERE comment_id = ?";
        $con->query($sql, array($comment_id));
        return $con->rowCount();
    }

    public static function editById($comment, $comment_id)
    {
        $con = DB::conn();
        $sql = "UPDATE " . self::TABLE_NAME . " SET comment = ? WHERE comment_id = ?";
        $con->query($sql, array($comment, $comment_id));
        return $con->lastInsertId();
    }

}