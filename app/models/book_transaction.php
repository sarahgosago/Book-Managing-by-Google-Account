<?php

class BookTransaction extends Model
{
    const TABLE_NAME = 'book_transactions';

    public static function lendingInfo($book_id, $lender, $staff_id, $book_title)
    {
        $con = DB::conn();
        $date_borrowed = date('Y-m-d');
        $con->insert(self::TABLE_NAME, array("book_id" => $book_id, "staff_id" => $staff_id, "book_title" => $book_title,
                                            "lender"=>$lender, "date_borrowed"=>$date_borrowed, "date_returned"=>" "));
        return $con->lastInsertId();
    }

    public static function returningInfo($book_id, $lender, $staff_id, $book_title)
    {
        $con = DB::conn();
        $date_returned = date('Y-m-d');
        $con->insert(self::TABLE_NAME, array("book_id" => $book_id, "staff_id" => $staff_id, "book_title" => $book_title,
                                             "lender"=>$lender, "date_borrowed"=>" ", "date_returned"=>$date_returned));
        return $con->lastInsertId();
    }

    public static function getAllTransactions()
    {
        $con = DB::conn();
        $sql = "SELECT *, DATEDIFF(NOW(),date_borrowed) AS 'duration' FROM " . self::TABLE_NAME . "";
        return $con->rows($sql);
    }
}