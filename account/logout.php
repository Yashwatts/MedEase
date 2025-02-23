<?php
session_start();
if (isset($_SESSION['account_branch'])){
    unset($_SESSION['account_branch']);
    header("Location: ../login/login.php");
    exit();
}
?>