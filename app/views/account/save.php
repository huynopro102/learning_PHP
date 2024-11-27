<?php include 'app/views/shares/header.php'; ?>

<div class="card-body p-5 text-center">
    <?php if (isset($status)) : ?>
        <?php if ($status === true) : ?>
            <div class="alert alert-success">
                <h4>Registration Successful!</h4>
                <p>Your account has been created successfully.</p>
                <a href="/WEBBANHANG/account/login" class="btn btn-primary">Login Now</a>
            </div>
        <?php else : ?>
            <div class="alert alert-danger">
                <h4>Registration Failed!</h4>
                <p>There was an error creating your account. Please try again.</p>
                <a href="/WEBBANHANG/account/register" class="btn btn-secondary">Try Again</a>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <div class="alert alert-warning">
            <h4>Unexpected Error!</h4>
            <p>We couldn't determine the status of your registration.</p>
            <a href="/WEBBANHANG/account/register" class="btn btn-secondary">Go Back</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>
