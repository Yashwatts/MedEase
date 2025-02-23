<?php
session_start();
if (isset($_SESSION['employee'])){
    unset($_SESSION['employee']);
    header("Location: ../login/login.php");
    exit();
}
?>