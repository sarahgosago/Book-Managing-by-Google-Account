$(document).ready(function () {
    $("#tag1").tagsInput({

    });

    $('.pdf').click(function () {
        $('.hide_content').show("slide");
    });

    $('#hardbound').click(function () {
        $('.hide_content').hide("slide");
    });

    $('#photo').change(function () {
        console.log("");
        readURL(this);
    });

    $('#save-book').click(function (e) {
        var date = $('#published_date').val();
        var IsChecked = $('input[name=optionsRadios]').is(':checked');
        var pdfChecked = $('.pdf').is(':checked');
        var link_id = $('.pdf_link_id').val();
        var has_error = false;

        console.log($('input[name=optionsRadios]'));
        if (!IsChecked) {
            alert("Please Select if PDF or Hardbound.");
        }

        if (pdfChecked && link_id.length <= 5) {
            alert('Please put a link.');
            $('.pdf_link_id').addClass('has-error');
            has_error = true;
        } else {
            $('.pdf_link_id').removeClass('has-error');
            $('.pdf_link_id').addClass('has-success');
        }

        if ($('#book_name').val() == "") {
            $('#group_book_name').addClass('has-error');
            $('.icon').addClass('glyphicon glyphicon-remove form-control-feedback');
            has_error = true;
        }
        if (date.length !== 0 && !$.isNumeric(date)) {
            $('#date').addClass('has-error');
            $('.dateIcon').addClass('glyphicon glyphicon-remove form-control-feedback');
            has_error = true;
        }
        if (date.length > 4) {
            $('#date').addClass('has-error');
            $('.dateIcon').addClass('glyphicon glyphicon-remove form-control-feedback');
            has_error = true;
        } else if (date.length > 1 && date.length == 3) {
            $('#date').addClass('has-error');
            $('.dateIcon').addClass('glyphicon glyphicon-remove form-control-feedback');
            has_error = true;
        }

        if (has_error) {
            e.preventDefault();
        } else {
            $('#group_book_name').removeClass('has-error');
            $('#group_book_name').addClass('has-success');
            $('.icon').removeClass('glyphicon glyphicon-remove form-control-feedback');
            $('.icon').addClass('glyphicon glyphicon-ok form-control-feedback');
            $('#date').removeClass('has-error');
            $('#date').addClass('has-success');
            $('.dateIcon').removeClass('glyphicon glyphicon-remove form-control-feedback');
            $('.dateIcon').addClass('glyphicon glyphicon-ok form-control-feedback');
            $('.pdf_link_id').removeClass('has-error');
            $('.pdf_link_id').addClass('has-success')
        }

    });

    $('#add-book-form').ajaxForm(function (result) {
        var form = $('#add-book-form');
        var text = $("input[type=text], textarea");
        var tag1 = $('#tag1').val();

        alert(result.message);

        form.find("input[type=text], textarea").val("");

        $("input[name=optionsRadios]").prop('checked', false);
        $('#group_book_name').removeClass('has-success');
        $('.icon').removeClass('glyphicon glyphicon-ok form-control-feedback');
        $('#date').removeClass('has-success');
        $('.dateIcon').removeClass('glyphicon glyphicon-ok form-control-feedback');
        $('.pdf_link_id').removeClass('has-success');
        document.location.reload(true);
    });

});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#photo-preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}


