<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>B.M.S.</title>
    <link rel="icon" type="image/png" href="/bootstrap/images/book.ico">
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/bootstrap/css/bms.css" rel="stylesheet">
</head>
<body style="overflow:hidden">
<?php echo $_content_ ?>
<script src="/bootstrap/jquery/jquery.js" type="text/javascript"></script>
<script src="/bootstrap/js/bootstrap.js" type="text/javascript"></script>
<script>
    console.log(<?php eh(round(microtime(true) - TIME_START, 3)) ?> +'sec');
</script>
</body>
</html>
