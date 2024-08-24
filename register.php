<?php
require_once "./connection.php"; // Ensure this is correctly placed and the path is correct

if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['names']) || empty($_POST['email']) || empty($_POST['pass']) || empty($_POST['rol'])) {
        $alert = 'All fields are required';
    } else {
        $names = mysqli_real_escape_string($conn, $_POST['names']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $pass = md5(mysqli_real_escape_string($conn, $_POST['pass']));
        $rol = mysqli_real_escape_string($conn, $_POST['rol']);

        $query = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");
        $result = mysqli_num_rows($query);

        if ($result > 0) {
            $alert = 'The email already exists';
        } else {
            $query_insert = mysqli_query($conn, "INSERT INTO user(names, email, pass, rol) VALUES('$names', '$email', '$pass', '$rol')");
            if ($query_insert) {
                $alert = 'User registered successfully';
                header('location: index.php');
            } else {
                $alert = 'Error registering user';
            }
        }
        mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Pos </b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Register in to start your session</p>

                <form action="" method="post" autocomplete="off">
                    <div class="input-group mb-3">
                    <input type="text" name="names" class="form-control" placeholder="Name">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user-plus"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="pass" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <select name="rol" id="rol" class="form-control">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                        </select>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit"  class="btn btn-primary btn-block">Register</button>
                            <div><?php echo isset($alert) ? $alert : ''; ?></div>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="assets/dist/js/adminlte.min.js"></script>
</body>

</html>