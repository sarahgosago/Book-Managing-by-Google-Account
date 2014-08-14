<?php
class RenderBook
{
    const SORT_RATE   = 'rate';
    const SORT_AUTHOR = 'book_author';
    const SORT_TITLE  = 'book_title';
    const SORT_DATE   = 'published_date';

    public static function doRender($book, $sorting){
        $book["book_source"]  = 'book_covers/' . $book['book_id'] . '.jpg';
        $book["book_title"]   =  substr($book['book_title'],0,70) . (strlen($book['book_title']) > 70?'...':'');
        $book['book_author'] = ($book['book_author']?$book['book_author']:'no author');

        $overlay = '';
        $color   = '';

        if ($book['is_disabled']) {
            $color   = 'overlay-blue';
        }elseif(!$book['is_available']){
            $color   = 'overlay-red';
        }

        $overlay = self::getOverlay($color, $book, $sorting);

        return '<div class="book-slot">
                    <a class="book book-pop-up ' . strtolower($book["type"]) . '" book_id="' . $book['book_id'] . '" inventory_id="' . $book['inventory_id'] . '" link_id="' . $book['link_id'] . '" type="' . $book['type'] . '" published_date="' . $book['published_date'] . '" language="' . $book['language'] . '" publisher="' . $book['publisher'] . '" isbn="' . $book['isbn'] . '" book_info="' . $book['book_info'] . '" book_title="' . $book['book_title'] . '" book_author="' . $book['book_author'] . '" data-toggle="modal">
                        <img class="book-size" onerror="this.src=\'/book_covers/no_cover.jpg\'" src="/book_covers/' . $book['book_id'] . '.jpg">
                        '.$overlay.'
                        <div class="book-type-pdf"><span class="' . (strtolower($book["type"]) == "pdf" ? "pdf-marker" : "") . '"></span></div>
                    </a>
                    '.($book["lender"] && $book["lender"] != 'NULL'?'<div class="on-slot">
                                                                         <span class="glyphicon glyphicon-user borrower btn-warning" title="'.$book["lender"].'"></span>
                                                                         <span class="show-borrower">Borrowed By: '.$book["lender"].'</span>
                                                                     </div>':'').'
                </div>';
    }

    public static function renderOverlay($color, $data){
        return '<div class="book-overlay show-title '.$color.'"><h6>'.$data.'</h6></div>';
    }

    public static function getOverlay($color, $book, $sorting){
        switch($sorting){
            case self::SORT_AUTHOR:
                return self::renderOverlay($color,$book['book_author']);
                break;
            case self::SORT_TITLE:
                return self::renderOverlay($color,$book['book_title']);
                break;
            case self::SORT_DATE:
                return self::renderOverlay($color,$book['published_date']);
                break;
            case self::SORT_RATE:
                return self::renderOverlay($color,str_repeat('<span class="glyphicon glyphicon-star"></span>',intval($book['avg_rate'])));
                break;
            default:
                if(!file_exists($book['book_source'])){
                    return self::renderOverlay($color,$book['book_title']);
                }
                break;
        }
    }
}