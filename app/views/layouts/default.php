<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>B.M.S.</title>
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/bootstrap/css/dataTables.bootstrap3.css" rel="stylesheet">
    <link href="/bootstrap/css/bms.css" rel="stylesheet">
    <link href="/bootstrap/css/autocomplete.css" rel="stylesheet">
    <link href="/bootstrap/css/tags.css" rel="stylesheet">
    <link href="/bootstrap/css/jquery.dataTables.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="/bootstrap/images/book.ico">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'>
    <script src="/bootstrap/jquery/jquery.js" type="text/javascript"></script>
    <script src="/bootstrap/js/bootstrap.js" type="text/javascript"></script>
    <script src="/bootstrap/js/jquery.form.js" type="text/javascript"></script>
    <script src="/bootstrap/js/jquery.dataTables.js" type="text/javascript"></script>
    <script src="/bootstrap/js/dataTables.bootstrap3.js" type="text/javascript"></script>
    <script src="/bootstrap/js/update_book.js" type="text/javascript"></script>
    <script src="/bootstrap/js/add_book.js" type="text/javascript"></script>
    <script src="/bootstrap/js/manage.js" type="text/javascript"></script>
    <script src="/bootstrap/js/tags.js" type="text/javascript"></script>
    <script src="/bootstrap/js/jquery.autocomplete.js" type="text/javascript"></script>
    <script src="/bootstrap/js/add_admin.js" type="text/javascript"></script>
</head>

<body>
<nav class="navbar navbar-inverse" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand bms" href="/main/manage"><span class="glyphicon glyphicon-th-large"></span>
                B.M.S.</a>
        </div>
        <ul class="nav navbar-nav">
            <?php if (MySession::get("user_type")) {
                echo '<li class=""><a href="/main/manage">Manage Books</a></li>';
            } ?>

            <?php if (MySession::get("user_type")) {
                echo '<li><a href="/main/addBook">Add Book</a></li>';
            } ?>

            <?php if (MySession::get("user_type")) {
                echo '<li><a href="/main/addAdmin">Accounts</a></li>';
            } ?>

            <?php if (MySession::get("user_type")) {
                echo '<li><a href="/main/logs">Transactions</a></li>';
            } ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="/main/home" target="_blank">View Book List</a></li>
        </ul>
        <div class="collapse navbar-collapse " id="bs-example-navbar-collapse-1">
            <div class="dropdown  navbar-right">
                <a role="button" data-toggle="dropdown" method="get" data-target="#" href="/page.html"
                   class="pad username" name="username" value="username">
                    <?php echo MySession::get("username"); ?> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                    <li><a class="logout" href="/main/logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<div class="wrapper">
    <?php echo $_content_ ?>
</div>
<script>
    $(document).ready(function () {
        $('.bms').click(function () {
            document.location.reload(true);
        });

        $(".sorter").click(function () {
            $("#sort_by").val($(this).attr("data-sort"));
            $("#search-form").submit();
        });
    });
    console.log(<?php eh(round(microtime(true) - TIME_START, 3)) ?> +'sec');
</script>
</body>
</html>
