<div class="layout-color container white-bg" id="container">
    <div class="page-header">
        <h2>Accounts Management</h2>
    </div>

    <form role="form" class="form-inline" id="add-admin-form" action="/main/addAdmin" method="post"
          enctype="multipart/form-data">
        <p class="help-block">allow new email to admin page</p>

        <div class="form-group" id="group-username">

            <label class="sr-only">Email</label>
            <input type="text" class="form-control" id="staff" name="staff"
                   placeholder="Enter Name..." required="required">
            <input type="hidden" name="username" id="username">
            <input type="hidden" name="callback" value="json">
            <span class="icon"> </span>

            <button type="submit" class="btn btn-primary" id="save-admin"><span class="glyphicon glyphicon-plus"></span>
                Add
            </button>
        </div>
    </form>
    <hr>

    <input type='hidden' id='current_page'/>
    <input type='hidden' id='show_per_page'/>
    <table id="admin-results" class="table table-size table-striped table-bordered table-condensed">
        <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($admins as $admin) {
            $table_row = '<tr>' .
                '<td>' . $admin['id'] . '</td>' .
                '<td>' . $admin['firstname'] . '</td>' .
                '<td>' . $admin['lastname'] . '</td>' .
                '<td>' . $admin['username'] .
                '<a class="glyphicon glyphicon-trash pull-right delete-admin" data-admin-username="' . $admin['username'] . '" data-admin-id="' . $admin['id'] . '" style="color:red" href="addAdmin"> ' .
                '</a>' . '</td>';
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
            $('#staff').autocomplete({
                width: 204,
                lookup: options,
                onSelect: function (data) {
                    $("#username").val(data.data);
                }
            });
    });
</script>
