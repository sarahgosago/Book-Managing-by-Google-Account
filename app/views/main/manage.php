<div class="modal fade modal-dialog-manage modal-scroll" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Lend Book</h4>
            </div>
            <div class="modal-body">
                <div class="modal-body-manage">
                    <div class="col-md-7 well col-md-offset-3">
                        <form role="form" id="save-lend-book-frm" action="/main/lendBook" method="post">
                            <div class="form-group">
                                <label for="lender">Enter Lender's Name</label>
                                <input type="text" class="form-control autocomplete" id="lender" name="lender"
                                       placeholder="...">
                                <input type="hidden" name="book_title" id="book_title">
                                <input type="hidden" name="staff_id" id="staff_id">
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="page_next" value="">
                                <input type="hidden" name="book_id" id="book_id" value="">
                                <input type="submit" class="btn btn-primary" id="save-lend-book" data-dismiss="modal"
                                       value="Lend">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-dialog-manage modal-scroll" id="myModal2" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel2">Return Book</h4>
            </div>
            <div class="modal-body">
                <div class="modal-body-manage">
                    <div class="col-md-7 well col-md-offset-3">
                        <form role="form" id="save-return-book-frm" action="/main/returnBook" method="post">
                            <div class="form-group">
                                <label for="lender2">Enter Lender's Name</label>
                                <input type="text" class="form-control lend-autocomplete" id="lender2" name="lender2"
                                       placeholder="...">
                                <input type="hidden" name="staff_id2" id="staff_id2">
                            </div>

                            <div class="modal-footer">
                                <input type="hidden" name="page_next" value="">
                                <input type="hidden" name="book_id2" id="book_id2" value="">
                                <input type="submit" class="btn btn-primary" id="save-return-book" data-dismiss="modal"
                                       value="Return">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="wrapper-content">
    <ul class="pagination" id="page_navigation">
    </ul>
    <input type='hidden' id='current_page'/>
    <input type='hidden' id='show_per_page'/>

    <div class="btn-group">
        <div class="btn-group">
            <button type="button" class="btn btn-default disabled" data-toggle="dropdown">
                Display
            </button>
        </div>
        <button type="button" class="btn btn-default filter" id="all" value="">All</button>
        <button type="button" class="btn btn-default filter" id="hardbound" value="hardbound">Hardbound</button>
        <button type="button" class="btn btn-default filter" id="pdf" value="pdf">PDF</button>

        <button type="button" class="btn btn-default filter" id="available" value="available">Available</button>
        <button type="button" class="btn btn-default filter" id="lent" value="lent">Not Available</button>

    </div>
    <hr>

    <table id="book-results" class="table table-size table-striped table-bordered table-condensed">
        <thead>
        <tr>
            <th>ID</th>
            <th>Inventory ID</th>
            <th>Image</th>
            <th>Book Name</th>
            <th>Tags</th>
            <th>Type</th>
            <th style="display: none">Availability</th>
            <th>View Details</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($book_results as $book) {
            $table_row = '<tr>' .
                '<td class="book_id">' . $book['book_id'] . '</td>' .
                '<td>' . $book['inventory_id'] . '</td>';

            if (has_book_cover($book['book_id'])) {
                $table_row .= '<td>' . '<img class="thumbnail" src="/book_covers/' . $book['book_id'] . '.jpg">' . '</td>';
            } else {
                $table_row .= '<td>' . '<img class="thumbnail" src="/book_covers/no_cover.jpg">' . '</td>';
            }
            $table_row .= '<td><blockquote>' . $book['book_title'] . '<br><hr class="hr-less"><span class="muted">' . ($book['isbn'] ? $book['isbn'] : 'NO ISBN') . '</span><br>' . '<footer><cite title="Source Title">' . ($book['lender'] && $book['lender'] != "NULL" ? 'Borrowed by ' . $book['lender'] : 'Available') . '</cite></footer></blockquote></td>' .
                '<td>' . $book['tags'] . '</td>' .
                '<td>' . $book['type'] . '</td>';
            if ($book['is_available']) {
                $table_row .= '<td style="display: none">' . 'Available' . '</td>' .
                    '<td>';
            } else {
                $table_row .= '<td style="display: none">' . 'Lent' . '</td>' .
                    '<td>';
            }

            $table_row .= '<div class="btn-group-vertical">';

            if ($book['is_available']) {
                $table_row .= '<a class="btn btn-primary btn-sm lend-pop-up" id="lend' . $book['book_id'] . '" href="#myModal" data-target="#myModal">Lend</a>';
            } else if (!$book['is_available'] && !in_array($book['type'], array("PDF", "MOBI", "EPUB"))) {
                $table_row .= '<a class="btn btn-primary btn-sm return-pop-up" id="return' . $book['book_id'] . '" href="#myModal2" data-target="#myModal2">Return</a>';
            }

            if ($book['is_disabled']) {
                $table_row .= '<a class="btn btn-primary btn-sm enable-book" data-enable-book-name="' . $book['book_title'] . '" data-enable-book-id="' . $book['book_id'] . '">Enable</a>';
            } else if (!$book['is_disabled']) {
                $table_row .= '<a class="btn btn-primary btn-sm disable-book"  data-disable-book-name="' . $book['book_title'] . '" data-disable-book-id="' . $book['book_id'] . '">Disable</a>';
            }


            $table_row .= '<a class="btn btn-primary btn-sm update_book" href="/main/updateBook&book_id=' . $book['book_id'] . '" target="_self"><span class="glyphicon glyphicon-edit"></span> Edit</a></li>';
            $table_row .= '<a class="btn btn-primary btn-sm delete-current-book delete-book" data-book-name="' . $book['book_title'] . '" data-delete-book-id="' . $book['book_id'] . '"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>';
            $table_row .= '</div>';
            $table_row .= '</td>' .
                '</tr>';
            echo $table_row;
        }
        ?>
        </tbody>
    </table>
    <br/>
</div>

<script>
    $(document).ready(function () {
        options = <?php echo json_encode($options) . "\n"; ?>

            $('#lender').autocomplete({
                width: 300,
                lookup: options,
                onSelect: function (data) {
                    $("#staff_id").val(data.data);
                }
            });

        $('#lender2').autocomplete({
            width: 300,
            lookup: options,
            onSelect: function (data) {
                $("#staff_id2").val(data.data);
            }
        });

        $(document).on("click", "a.delete-book", function (e) {
            var id = $(this).data("delete-book-id");
            var book_name = $(this).data("book-name");
            if (confirm("Are you sure you want to delete '" + book_name + "'?")) {
                $.post('/main/manage', {id: id, callback: 'json' }, function () {
                    window.location.reload(true);
                    e.preventDefault();
                });
            }
        });

        $(document).on("click", "a.disable-book", function (e) {
            var disable_id = $(this).data("disable-book-id");
            var disable_book_name = $(this).data("disable-book-name");

            if (confirm("Are you sure you want to disable '" + disable_book_name + "'?")) {
                $.post('/main/manage', {disable_id: disable_id, callback: 'json' }, function () {
                    window.location.reload(true);
                    e.preventDefault();
                });
            }
        });

        $(document).on("click", "a.enable-book", function (e) {
            var enable_id = $(this).data("enable-book-id");
            var enable_book_name = $(this).data("enable-book-name");

            if (confirm("Are you sure you want to enable '" + enable_book_name + "'?")) {
                $.post('/main/manage', {enable_id: enable_id, callback: 'json' }, function () {
                    window.location.reload(true);
                    e.preventDefault();
                });
            }
        });


        $(".filter").click(function () {
            $("#book-results_filter input").val($(this).val()).keyup();
            $("#book-results_filter input").val("");
        });
    });
</script>