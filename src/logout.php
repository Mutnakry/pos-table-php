<?php
    // session_start();
    // session_destroy();

    // header('location: ../');
?><?php
include('../connection.php');
session_start();
session_unset();
session_destroy();
header("Location: ../"); // Redirect to login page after logout
?>


