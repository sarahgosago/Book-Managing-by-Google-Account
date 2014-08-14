<?php
function has_book_cover($book_id)
{
    return file_exists(APP_DIR . 'webroot/book_covers/' . $book_id . '.jpg');
}