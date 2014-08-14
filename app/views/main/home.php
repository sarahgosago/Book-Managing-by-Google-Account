<style>
    .undo-favorite {
        color: #FFFFFF;
    }

    .favorite {
        color: rgba(132, 16, 8, 0.97);
    }
</style>
<div id="tag-lists">
    <div class="content-tag">
        <ul class="tags">
            <?php
            $tags1 = "";

            foreach ($tag_listed as $tag) {
                $tags1 .= '<li><a href="/main/home?tag_val=' . $tag["tag_name"] . '&filter=' . (isset($book_filter) ? $book_filter : "") . '" id="tag_val">' . $tag["tag_name"] . '<span class="tag-count">' . $tag['count'] . '&nbsp&nbsp </span></a></li>';
            }

            echo $tags1;
            ?>
        </ul>
    </div>
</div>
<div id="book-stand">
    <?php
    $book_list = array_chunk($books, 6);
    $book_count = count($book_list);
    if (!$books) {
        ?>
        <div class="book-row">
            <div class="no-book">
                <span class="glyphicon glyphicon-exclamation-sign"></span> No Books Found in the Shelf.
                <br>
                <h5>how about filtering it by tags<h5>
            </div>
        </div>
    <?php
    }

    foreach ($book_list as $row => $list) {
        echo '<div class="book-row">';

        foreach ($list as $book) {
            echo RenderBook::doRender($book, $sorting);
        }

        echo '</div>';
    }
    ?>
</div>
<input type="hidden" id="max-book-count" value="<?php echo $total_books_count ?>"/>
<input type="hidden" id="searched-book-name" value="<?php echo $book_name ?>"/>
<input type="hidden" id="current-book" value="<?php echo count($books) ?>"/>
<input type="hidden" id="book-count" value="<?php echo $book_count ?>"/>
<input type="hidden" id="current-user-id" value="<?php echo $user_id; ?>"/>
<input type="hidden" id="book-filter" value="<?php echo $book_filter; ?>"/>
<input type="hidden" id="tag" value="<?php echo $tag_val; ?>"/>

<div id="book-preview-info">
    <div class="book_preview col-md-3">
        <div class="fullscreen-btn-links">
        <a class="cover-link" href=""> <img class="book_cover item" src="" alt=""/></a>
        </div>
        <div class="" id="book-full-screen-info" style="display:none">
        </div>

        <div class="read_links btns-overlay hide_content" style="">

        </div>
    </div>
    <div class="col-md-8 modal-info">
        <div id="book-text-info">
            <a class="title"><h2></h2></a>

            <div class="list-info">
                <div class="main-info">
                    <ul class="list-unstyled">
                        <li class=""><label id="author-info"> Author: &nbsp;</label><span
                                class="author highlight"></span></li>
                        <li><label id="pub-info"> Publisher: &nbsp;</label><span class="publisher highlight"></span>
                        </li>
                        <li><label id="lang-info"> Language: &nbsp;</label><span class="language"></span></li>
                        <li><label id="date-info"> Date Published: &nbsp;</label><span class="date"> </span></li>
                        <li><label id="type-info"> Type: &nbsp;</label><span class="type"> </span></li>
                        <li><label id="link-info"><span class="hide_content">Read:&nbsp;</span></label><span
                                class="link_id" href=""
                                target="_blank"> </span>
                        </li>
                        <li><a class="info" href="" target="_blank"><span class="glyphicon glyphicon-share-alt"></span>
                                More Info</a></li>
                    </ul>
                </div>
                <div class="other-info">
                    <div id="book_rating" class="rateit" data-rateit-value="0" data-rateit-resetable="false"
                         data-rateit-ispreset="true" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1">
                        <div id="book_rate" style="float: right; margin-left: 8px ">
                            ( <span id="raters_count">5</span> )
                        </div>
                    </div>
                    <input type="hidden" name="rate_book_id" id="rate_book_id" value="">

                    <div id="my_rate">You have not rate it yet!</div>
                </div>
            </div>
        </div>

        <div id="comment-lists" class="col-md-7">
            <h4><span class="glyphicon glyphicon-th-list"></span> Comment Lists</h4>

            <div class="input-group">
                <span class="input-group-addon disabled">Comment</span>

                <div class="add-comment">
                    <input type="text" class="form-control" data-book-id="" id="comment" name="comment">
                </div>
                <span type="submit" class="input-group-addon btn btn-primary save-comment">Post</span>
            </div>
            <input type="hidden" name="callback" value="json">
            <hr class="hr-less dashed">
            <div class="comment">
            </div>
        </div>
    </div>

    <div class="col-md-1 book-info-options">
        <button id="view-comments" data-toggle="button" type="button" class="btn btn-default btn-special"
                data-book-id="">
            <span class="glyphicon glyphicon-chevron-up"></span> Show Comments
        </button>
        <button id="hide-book-info" type="button" class="btn btn-default btn-special">
            <span class="glyphicon glyphicon-remove"></span> Close
        </button>
        <button id="favorite" class="btn btn-default btn-special">
            <span class="glyphicon glyphicon-heart favorite-icon" title="Favorite"></span>
            &nbsp;x <span id="total_favor">0</span>
        </button>
    </div>

    <div class="" style="display: block; bottom: 10px; position: fixed; right:10px;">
        <label id="lender-info">Borrowed By: </label>
        <span class="lender"> </span>
        <br>
    </div>
</div>
<div id="book-short-info">
    <div class="book_preview col-md-2">
        <div class="inventory-id">12120004</div>
    </div>
    <div class="col-md-9">
        <a class="title highlight" href="" target=""><h4></h4></a>
        <span class="author highlight"></span>
    </div>
    <div class="col-md-1">
        <button id="hide-short-info" type="button" class="btn btn-default btn-special">
            <span class="glyphicon glyphicon-chevron-down"></span>
        </button>
    </div>
</div>
<div id="show-info" class="col-md-1">
    <button id="show-short-info" type="button" class="btn btn-default btn-special">
        <span class="glyphicon glyphicon-chevron-up"></span>
    </button>
</div>

<script>
    $(document).ready(function () {
        var options =
        <?php echo json_encode($options) . "\n"; ?>
        var max_books = $("#max-book-count").val();
        var showed_books = $("#current-book").val();

        $(".search-results span").html(showed_books + "/" + max_books);

        $("#book-name-searched").autocomplete({
            width: 890,
            lookup: options,
            onSelect: function (data) {
                $("#book_res_title").val(data.value);
            }
        });

        $("#search-btn").click(function (e) {
            e.preventDefault();
            var book_name = encodeURIComponent($("#book-name-searched").val());
            if (book_name == "") {
                window.location = '/main/home'
            } else {
                window.location = '/main/home?book_name=' + book_name;
            }
        });

        var tooltip_values = ['bad', 'poor', 'ok', 'good', 'super'];
        var $book_rating = $("#book_rating.rateit");
        $book_rating.bind('over', function (event, value) {
            $(this).attr('title', tooltip_values[value - 1]);
        });

        $book_rating.bind('rated reset', function () {
            var ri = $(this);
            var rate = ri.rateit('value');
            var book_id = $('#rate_book_id').val();
            var user_rate = $("b#user_rate").html();
            var confirm_message = "Are you sure you want to rate this book with " + rate + " star(s)?";
            var rate_success_msg = "Your rate is successfully saved.";
            if (user_rate != undefined) {//check if not yet rated
                confirm_message = "Are you sure you want to change your rating on this book from " + user_rate + " to " + rate + " star(s)?";
                rate_success_msg = "Your rate is successfully updated."
            }

            if (confirm(confirm_message)) {
                $.post('/main/saveRating', { book_id: book_id, rate: rate }, function (result) {
                    if (result.success) {
                        updateRatingDisplay($book_rating, book_id, result);
                        alert(rate_success_msg);
                    } else {
                        alert("Seems there something wrong in saving your rating.");
                    }
                });
            }
        });

        $("#favorite").click(function () {
            var book_id = $('#rate_book_id').val();
            var favorite = $(this);
            var is_favorite = $(this).hasClass('favorite');
            if (is_favorite) {
                $.post('/main/undoFavorite', { book_id: book_id }, function (result) {
                    if (result.success) {
                        $('span#total_favor').text(result.total_favor);
                        favorite.removeClass('favorite').addClass('undo-favorite');
                        $favorite.attr('title', 'Favorite');
                    } else {
                        alert("Seems there something wrong.");
                    }
                });
            } else {
                $.post('/main/makeFavorite', { book_id: book_id }, function (result) {
                    if (result.success) {
                        $('span#total_favor').text(result.total_favor);
                        favorite.removeClass('undo-favorite').addClass('favorite');
                        $favorite.attr('title', 'Favorited');
                    } else {
                        alert("Seems there something wrong.");
                    }
                });
            }
        });
    });
</script>
