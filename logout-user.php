<?php
session_start();
session_unset();
session_destroy();
header('location: https://github.com/shabi983/SignUp/blob/main/login-user.php');
?>
