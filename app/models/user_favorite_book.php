<?php

class UserFavoriteBook extends AppModel
{
    const TABLE_NAME = 'user_favorite_book';

    public static function makeFavorite($book_id, $user_id)
    {
        $con = DB::conn();
        $con->insert(self::TABLE_NAME, array('book_id' => $book_id, 'user_id' => $user_id));
        return $con->lastInsertId();
    }

    public static function undoFavorite($book_id, $user_id)
    {
        $con = DB::conn();
        $sql = "DELETE FROM user_favorite_book WHERE book_id = :book_id AND user_id = :user_id";
        $con->query($sql, array('book_id' => $book_id, 'user_id' => $user_id));
        return $con->lastInsertId();
    }

    public static function countInfavor($book_id)
    {
        $con = DB::conn();
        return $con->value(
            'SELECT count(*) as user_in_favor FROM user_favorite_book WHERE book_id = ?',
            array($book_id)
        );
    }

    public static function isFavorite($book_id, $user_id)
    {
        $con = DB::conn();
        return $con->row(
            'SELECT * FROM ' . self::TABLE_NAME . ' WHERE book_id=:book_id AND user_id=:user_id',
            array('book_id' => $book_id, 'user_id' => $user_id)
        );
    }

    public static function getFavorite($user_id)
    {
        $con = DB::conn();
        $sql = 'SELECT * FROM books WHERE book_id IN (SELECT book_id from ' . self::TABLE_NAME . ' WHERE user_id=?)';
        return $con->rows($sql, array($user_id));
    }
}
