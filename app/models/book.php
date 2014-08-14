<?php

class Book extends Model
{
    const TYPE_HARDBOUND = 'hardbound';
    const TYPE_PDF = 'pdf';
    const TABLE_NAME = 'books';

    public static function findBookById($book_id)
    {
        $con = DB::conn();
        $sql = "SELECT * FROM " . self::TABLE_NAME . " WHERE book_id = ?";
        return $con->row($sql, array($book_id));
    }

    public static function updateBook($book_data)
    {
        $db = DB::conn();
        $db->update('books', $book_data, array('book_id' => $book_data['book_id']));
        return $book_data['book_id'];
    }

    public static function addBook($book_name, $volume, $author, $publisher, $published_date, $language, $type = '', $tags, $book_info, $link_id, $isbn)
    {
        $con = DB::conn();
        $con->insert(self::TABLE_NAME, array("book_title" => $book_name, "volume" => $volume, "book_author" => $author,
                                             "publisher" => $publisher, "published_date" => $published_date,
                                             "language" => $language, "type" => $type, "isbn" => $isbn, "tags" => $tags, "book_info" => $book_info, "link_id" => $link_id));
        return $con->lastInsertId();
    }

    public static function insertInventoryIdToNewlyAddedBook()
    {
        $con = DB::conn();
        $sql = "UPDATE " . self::TABLE_NAME . " SET inventory_id = (CONCAT(DATE_FORMAT(NOW(),'%y%m'), LPAD(book_id, '4', '0000' )))";
        $con->query($sql);
        return $con->lastInsertId();
    }

    public static function returnBook($book_id, $lender)
    {
        $con = DB::conn();
        $sql = "UPDATE " . self::TABLE_NAME .
               " SET is_available=1, lender='NULL' WHERE book_id=? AND lender=?";
        $con->query($sql, array($book_id, $lender));
        return $con->rowCount() ? true : false;
    }

    public static function lendBook($book_id, $lender)
    {
        $con = DB::conn();
        $con->update(self::TABLE_NAME, array("is_available"=>0, "lender"=>$lender), array("book_id" => $book_id));
        return $con->rowCount();
    }

    public static function getAllBooks()
    {
        $con = DB::conn();
        $sql = "SELECT * FROM " . self::TABLE_NAME . "";
        return $con->rows($sql);
    }

    public static function deleteBook($book_id)
    {
        $con = DB::conn();
        $sql = "DELETE FROM " . self::TABLE_NAME . " WHERE book_id = ?";
        $con->query($sql, array($book_id));
        return $con->rowCount();
    }

    public static function disableBook($book_id)
    {
        $con = DB::conn();
        $con->update(self::TABLE_NAME, array("is_disabled"=>1), array('book_id' => $book_id));
        return $con->rowCount();
    }

    public static function enableBook($book_id)
    {
        $con = DB::conn();
        $con->update(self::TABLE_NAME, array("is_disabled"=>0), array('book_id' => $book_id));
        return $con->rowCount();
    }

    public static function findBookByName($filters)
    {

        $title   = '%' . strtolower($filters["name"]) . '%';
        $start   = (int)$filters["start"];
        $limit   = 30;

        $sql = "SELECT * FROM books WHERE is_disabled = 0 AND LOWER(book_title) LIKE ?";

        if ($filters["type"]) {
            $sql .= ' AND type = "'.$filters["type"].'"';
        }

        if ($filters["sort"] == 'rate') {
            $sql = "SELECT b.*, AVG(ubr.rate) AS avg_rate, COUNT(ubr.user_id) AS rater_count
                    FROM books b
                    LEFT JOIN user_book_rating ubr ON b.book_id = ubr.book_id
                    WHERE is_disabled = 0 AND LOWER(book_title) LIKE ? ";

            if($filters["type"]){
                $sql .= ' AND type = "'.$filters["type"].'"';
            }

            $sql .= " GROUP BY b.book_id ORDER BY AVG(ubr.rate) DESC";
        } elseif ($filters["sort"] && $filters["sort"] != 'rate') {
            $sql .= " ORDER BY {$filters["sort"]} ASC ";
        }

        if (!$filters['sort']) {
            $sql .= " ORDER BY book_id DESC ";
        }

        if ($filters["all"] == false) {
            $sql .= " LIMIT {$filters['start']}, {$limit}";
        }

        $con = DB::conn();
        return $con->rows($sql, array($title));
    }

    public static function findBookName($book_id)
    {
        $con = DB::conn();
        $sql = "SELECT book_title FROM " . self::TABLE_NAME . " WHERE book_id = ?";
        return $con->value($sql, array($book_id));
    }

    public static function getAllDistinctTags($type)
    {
        $con = DB::conn();
        if($type){
            $sql = "SELECT DISTINCT tags FROM " . self::TABLE_NAME . " WHERE tags <>'' AND type = '{$type}' GROUP BY tags";
        }else{
            $sql = "SELECT DISTINCT tags FROM " . self::TABLE_NAME . " WHERE tags <>'' GROUP BY tags";
        }
        return $con->rows($sql);
    }

    public static function getTagCountByTag($tag_name)
    {
        $con = DB::conn();
        $tag_name = '%' . $tag_name . '%';
        $sql = "SELECT count(*) as count FROM " . self::TABLE_NAME . " WHERE tags LIKE ?";
        return $con->value($sql, array($tag_name));
    }

    public static function getBooksByTag($filters)
    {
        $start = (int)$filters['start'];
        $limit = 30;
        $tag = '%' . $filters['tag'] . '%';

        $sql = "SELECT *
                FROM books
                WHERE tags LIKE ? AND is_disabled = 0";

        if ($filters["type"]) {
            $sql .= ' AND type = "'.$filters["type"].'"';
        }

        if ($filters['sort'] == 'rate') {

            $sql = "SELECT b.*, AVG(ubr.rate) AS avg_rate, COUNT(ubr.user_id) AS rater_count
                    FROM books b
                    LEFT JOIN user_book_rating ubr ON b.book_id = ubr.book_id
                    WHERE b.tags LIKE ? AND b.is_disabled = 0";

            if ($filters["type"]) {
                $sql .= ' AND type = "'.$filters["type"].'"';
            }

            $sql .= " GROUP BY b.book_id ORDER BY AVG(ubr.rate) DESC";

        } elseif ($filters['sort'] && $filters['sort'] != 'rate') {
            $sql .= " ORDER BY {$filters['sort']} ASC ";
        }

        if (!$filters['sort']) {
            $sql .= " ORDER BY book_id DESC ";
        }

        if (!$filters['all']) {
            $sql .= " LIMIT $start, $limit";
        }

        $con = DB::conn();
        return $con->rows($sql, array($tag));
    }

    public static function getOptions()
    {
        $book_search_results = self::getAllBooks();
        $options = array();
        foreach ($book_search_results as $book_res) {
            $book_res_name = $book_res["book_title"];
            $options[] = array("value" => $book_res_name);
        }
        return $options;
    }

    public static function getUniqueTags($type)
    {
        $book_tags = self::getAllDistinctTags($type);
        foreach ($book_tags as $tags) {
            $new_tags = explode(",", $tags["tags"]);
            foreach ($new_tags as $tag) {
                $tag_lists[] = trim($tag);
            }
        }

        $tag_list = array_unique($tag_lists);
        foreach ($tag_list as $list) {
            $unique_tags[] = array("tag_name" => $list,
                "count" => self::getTagCountByTag($list));
        }
        return $unique_tags;
    }
}
