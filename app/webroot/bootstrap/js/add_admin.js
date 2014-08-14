$(document).ready(function () {
    $("#admin-results").dataTable();

    $("#save-admin").click(function (e) {
        var username = $("#username").val();

        if ($('#username').val() == "") {
            $('#group-username').addClass('has-error');
            $('.icon').addClass('glyphicon glyphicon-remove form-control-feedback');
        } else {
            $('#group-username').removeClass('has-error');
            $('#group-username').addClass('has-success');
        }

        $.post('/main/addAdmin', {username: $("#username").val(), callback: 'json' }, function (result) {
            alert(result.message);
            $('#group-username').removeClass('has-success');
            document.location.reload(true);
        });
        e.preventDefault();
    });

    $(".delete-admin").click(function (e) {
        var id = $(this).data("admin-id");
        var username = $(this).data("admin-username");
        if (confirm("Are you sure you want to delete " + username + "?")) {
            $.post('/main/addAdmin', {id: id, callback: 'json' }, function () {
                window.location.reload(true);
                e.preventDefault();
            });
        }
    });
});