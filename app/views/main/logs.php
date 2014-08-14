<div class="wrapper-content">
    <ul class="pagination" id="page_navigation">
    </ul>
    <input type='hidden' id='current_page'/>
    <input type='hidden' id='show_per_page'/>
    <table id="book-logs" class="table table-size table-striped table-bordered table-condensed">
        <thead>
        <tr>
            <th>Event ID</th>
            <th>Book ID</th>
            <th>Book Name</th>
            <th>Date Borrowed</th>
            <th>Date Returned</th>
            <th>Duration</th>
            <th>Borrowed by</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($book_transactions as $book) {

            $table_row = '<tr>' .
                '<td>' . $book['event_id'] . '</td>' .
                '<td class="book_id">' . $book['book_id'] . '</td>' .
                '<td>' . $book['book_title'] . '</td>' .
                '<td>' . $book['date_borrowed'] . '</td>';
            if ($book['date_returned'] == "0000-00-00") {
                $table_row .= '<td class="yellow-text">Pending</td>';
            } else {
                $table_row .= '<td>' . $book['date_returned'] . '</td>';
            }

            if ($book['date_returned'] == 0000 - 00 - 00) {
                if($book['duration']==0){
                    $table_row .= '<td class="red-text">Day' . '</td>';
                }elseif($book['duration']==1){
                    $table_row .= '<td class="red-text"> 1 Day' . '</td>';
                }elseif($book['duration']>1){
                    $table_row .= '<td class="red-text">' . $book['duration'] . ' Days' . '</td>';
                }
            } else {
                $table_row .= '<td class="green-text">' . 'Returned' . '</td>';
            }
            $table_row .= '<td>' . $book['lender'] . '<br> <br> <br> </td>' .
                '</td>' .
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
        $("#book-logs").dataTable({
            "order": [ 0, 'desc' ]
        });
    });
</script>
