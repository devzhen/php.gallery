<?php
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <div class="account-wall">
                <img class="profile-img" src="<?= BASE_URL . '/app/web/images/photo.png' ?>" alt="">
                <?php if (isset($_SESSION['auth_message'])): ?>
                    <h4 class="text-center login-title message" dg-temp-element="4"><?= $_SESSION['auth_message'] ?></h4>
                <?php endif; ?>
                <form class="form-signin" method="post" action="<?= BASE_URL . "/login" ?>">
                    <label>Login</label>
                    <input type="text" name="client_name" class="form-control" placeholder="admin" required autofocus value="admin">
                    <label>Password</label>
                    <input type="password" name="client_password" class="form-control" placeholder="admin" required value="admin">
                    <button class="btn btn-lg btn-primary btn-block" type="submit">
                        Sign in
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>