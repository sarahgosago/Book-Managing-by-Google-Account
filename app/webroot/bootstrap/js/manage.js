$(document).ready(function () {
    $("#book-results").dataTable({
        "aaSorting":[0,"desc"]
    });

    $(document).on("click", "a.lend-pop-up", function () {
        var modal = $("#myModal");
        var book_id = $(this).parents('tr').find(".book_id").text();
        modal.modal("show");
        modal.find("input#book_id").val(book_id);
    });

    $(document).on("submit", "#save-lend-book-frm", function () {
        alert('test');
    });

    $("#save-lend-book").click(function () {
        var modal = $("#myModal");
        var book_id = modal.find("input#book_id").val();
        var lender = modal.find("#lender").val();
        var staff_id = modal.find("#staff_id").val();

        $.post('/main/lendBook', {book_id: book_id, lender: lender, staff_id: staff_id }, function (result) {
            alert(result.message);
        });
        modal.find("#lender").val('');
        $('a.lend-pop-up#lend' + book_id).hide();
        $('a.return-pop-up#return' + book_id).show();
        window.location.reload();
    });


    $(document).on("click", "a.return-pop-up", function () {
        var modal = $("#myModal2");
        var book_id = $(this).parents('tr').find(".book_id").text();
        modal.modal("show");
        modal.find("input#book_id2").val(book_id);
    });

    $(document).on("submit", "#save-return-book-frm", function () {
        alert('test');
    });

    $("#save-return-book").click(function () {
        var modal = $("#myModal2");
        var book_id = modal.find("input#book_id2").val();
        var lender = modal.find("#lender2").val();
        var staff_id = modal.find("#staff_id2").val();

        $.post('/main/returnBook', {book_id: book_id, lender2: lender, staff_id2: staff_id}, function (result) {
            alert(result.message);
        });
        modal.find("#lender2").val('');
        $('a.lend-pop-up#lend' + book_id).show();
        $('a.return-pop-up#return' + book_id).hide();
        window.location.reload();
    });

    $(".add-link-btn").click(function () {
        link = '<div class ="link-pdf"><input type="text" class="form-control form-margin hide_content pdf_link_id" name="link_id[]" placeholder="Enter Link ID...">';
        link += '<button type="button" class="btn btn-danger btn-group-sm btn-inline glyphicon glyphicon-minus hide_content delete-link-btn delete-link-btn-style"></button></div>';
        $(".pdf-link").append(link);
        $('.hide_content').show("slide");
    });

    $(document).on("click", ".delete-link-btn", function () {
        $(this).parents('.link-pdf').remove();
    });
});