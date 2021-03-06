$(document).ready(function () {
    $("#tag2").tagsInput({
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
        var link_id = $('#link_id').val();
        var has_error = false;

        if (!IsChecked) {
            alert("Please Select if PDF or Hardbound.");
            has_error = true;
        }


        if (pdfChecked && link_id.length <= 5) {
            alert('Please put a link.');
            $('#link_id').addClass('has-error');
            has_error = true;
        } else {
            $('#link_id').removeClass('has-error');
            $('#link_id').addClass('has-success');
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
            $('#link_id').removeClass('has-error');
            $('#link_id').addClass('has-success')
        }
    });

    $('#update-book-form').ajaxForm(function (result) {
        var form = $('#update-book-form');
        var text = $("input[type=text], textarea");
        var tag2 = $('#tag2').val();

        alert(result.message);
        if (result.success) {
            window.location = '/main/manage';
        }

        $("input[name=optionsRadios]").prop('checked', false);
        $('#group_book_name').removeClass('has-success');
        $('.icon').removeClass('glyphicon glyphicon-ok form-control-feedback');
        $('#date').removeClass('has-success');
        $('.dateIcon').removeClass('glyphicon glyphicon-ok form-control-feedback');
        $('#link_id').removeClass('has-success');
        location.reload();
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
