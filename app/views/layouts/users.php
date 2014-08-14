<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>B.M.S.</title>
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/bootstrap/css/bms.css" rel="stylesheet">
    <link href="/bootstrap/css/autocomplete.css" rel="stylesheet">
    <link href="/rateit/src/rateit.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'>
    <link rel="icon" type="image/png" href="/bootstrap/images/book.ico">
    <script src="/bootstrap/jquery/jquery.js" type="text/javascript"></script>
    <script src="/bootstrap/js/bootstrap.js" type="text/javascript"></script>
    <script src="/bootstrap/js/home.js" type="text/javascript"></script>
    <script src="/bootstrap/js/jquery.autocomplete.js" type="text/javascript"></script>
    <script src="/rateit/src/jquery.rateit.js" type="text/javascript"></script>
</head>

<body>
<div id="overlay">
    <center>
        <div class="easy-load">
            <h2 id="wait">Loading More Books</h2>
            <div id="loader"></div>
        </div>
    </center>
</div>
<div class="navbar-wrapper">
    <div class="nav-controls">
        <div class="nav-user">
            <span class="glyphicon glyphicon glyphicon-user"></span>
            <?php echo MySession::get("username"); ?>
            <?php if (MySession::get("user_type")) { ?>
            | <a href="/main/manage">Admin</a>
            <?php } ?>
            | <a href="/main/logout">Logout</a>
        </div>
        <div class="padder">
            <a href="/main/home" class="dull-link title">Book List</a>
        </div>
        <form id="search-form" action="/main/home" class="navbar-form center-block" method="POST">
        <div class="search-field">
            <div class="input-group">
                <input type="text" class="form-control" name="book_name" id="book-name-searched" value="<?php echo (isset($book_name)) ? $book_name : ""; ?>">
                <input id="sort_by" type="hidden" class="form-control" name="sort_by" value="<?php echo (isset($sorting)) ? $sorting : ""; ?>">
                    <span class="input-group-btn">
                        <button id="search-btn" class="btn btn-success" type="submit" value=""><span class="glyphicon glyphicon-search"></span> Search</button>
                    </span>
                </div>
                <hr>
                <div class="btn-group btn-group-sm" data-toggle="buttons">
                    <button type="button" class="btn disabled">Sort By:</button>
                    <label id="sort-by-book_id" data-sort="book_id" class="btn btn-primary sorter">
                        <input type="radio" id="option1" value="1"> ID
                    </label>
                    <label id="sort-by-book_author" data-sort="book_author" class="btn btn-primary sorter">
                        <input type="radio" id="option2"> Author
                    </label>
                    <label id="sort-by-book_title" data-sort="book_title" class="btn btn-primary sorter">
                        <input type="radio" id="option3"> Name
                    </label>
                    <label id="sort-by-published_date" data-sort="published_date" class="btn btn-primary sorter">
                        <input type="radio" id="option4"> Date
                    </label>
                    <label id="sort-by-rate" data-sort="rate" class="btn btn-primary sorter">
                        <input type="radio" id="option5"> Rate
                    </label>
                </div>
                <div class="btn-group btn-group-sm" data-toggle="buttons">
                    <label class="btn btn-primary filters" filter="" id="filter-all">
                        <input type="radio" checked> All
                    </label>
                    <label class="btn btn-primary filters" filter="hardbound" id="filter-hardbound">
                        <input type="radio"> Bound
                    </label>
                    <label class="btn btn-primary filters" filter="pdf" id="filter-pdf">
                        <input type="radio"> PDF
                    </label>
                </div>
                <div class="btn-group search-results">
                    showing <span id="search-count">0</span> books found.
                </div>
                <div class="btn-group search-tag">
                    <span class="remove-tag glyphicon glyphicon-remove"></span>
                </div>
                <div class="quick-nav btn-group btn-group-sm pull-right" data-toggle="buttons">
                    <button type="button" class="btn" id="tags"><span class="glyphicon glyphicon-tags"></span></button>
                    <button type="button" class="btn" id="favor_btn" onclick="window.location='/main/favorite'"><span class="glyphicon glyphicon-heart"></span></button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="wrapper">
    <?php echo $_content_ ?>
</div>
<script>
    $(document).ready(function () {

        $("#sort-by-" + $("#sort_by").val()).click();

        $('.bms').click(function () {
            document.location.reload(true);
        });

        $(".sorter").click(function () {
            $("#sort_by").val($(this).attr("data-sort"));

            var sort_by      = $("#sort_by").val();
            var book_count   = $("#book-count").val();
            var book_name    = $("#searched-book-name").val();
            var filter       = $("#book-filter").val();
            var tag          = $("#tag").val();

            var href='/main/home?tag_val='+tag+'&filter='+filter+'&book_name='+book_name+'&sort_by='+sort_by;

            $("#search-form").attr("action",href);

            $("#search-form").submit();
        });

        $("#tags").click(function (){
            $("#tag-lists").slideToggle("fast");
        });
    });
    console.log(<?php eh(round(microtime(true) - TIME_START, 3)) ?> +'sec');
</script>
</body>
</html>
