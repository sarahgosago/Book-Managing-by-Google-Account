<div class="login-form">
    <div>
        <h2 class="text-center"> Book List </h2>
        <h4 class="text-center"> Book Management System Login </h4>
        <hr class="semi">
        <?php if (isset($_GET["error"])) { ?>
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Warning!</strong> <br>You do not have permission to use this application, please contact
                sysad.
            </div>
        <?php } else { ?>
        <?php } ?>
        <form method="post" action="/main/login">
            <input type="submit" name="submit" class="btn btn-wooden btn-group-justified btn-block"
                   value="Login with Google Account">
        </form>
    </div>
</div>
