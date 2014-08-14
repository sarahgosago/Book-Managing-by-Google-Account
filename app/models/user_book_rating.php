<?php

class UserBookRating extends AppModel
{
    const TABLE_NAME = 'user_book_rating';

    public static function isAlreadyRated($book_id, $user_id)
    {
        return self::getUserRatingForBook($book_id, $user_id) ? true : false;
    }

    public static function create($book_id, $rate, $user_id)
    {
        $con = DB::conn();
        $book_rating_val = array("book_id" => $book_id, "rate" => $rate, "user_id" => $user_id);
        $con->insert(self::TABLE_NAME, $book_rating_val);
        return $con->lastInsertId();
    }

    public static function update($book_id, $rate, $user_id)
    {
        $con = DB::conn();
        $con->update(self::TABLE_NAME, array("rate" => $rate), array("book_id" => $book_id, "user_id" => $user_id));
        return $con->rowCount();
    }

    public static function getBookRateInfo($book_id, $user_id)
    {
        $book_rating = self::getBookRating($book_id);
        $user_rating = self::getUserRatingForBook($book_id, $user_id);

        $book_rate["avg_rate"] = $book_rating ? $book_rating['avg_rate'] : 0;
        $book_rate["raters_count"] = $book_rating ? $book_rating['raters_count'] : 0;
        $book_rate["user_rate"] = $user_rating ? $user_rating["rate"] : 0;
        return $book_rate;
    }

    public static function getBookRating($book_id)
    {
        $con = DB::conn();
        return $con->row(
            'select avg(rate) as avg_rate, count(*) as raters_count from ' . self::TABLE_NAME . ' where book_id = ?',
            array($book_id)
        );
    }

    public static function getUserRatingForBook($book_id, $user_id)
    {
        $con = DB::conn();
        return $con->row(
            'SELECT * from ' . self::TABLE_NAME . ' where book_id = :book_id AND user_id = :user_id',
            array("book_id" => $book_id, "user_id" => $user_id)
        );
    }
}
