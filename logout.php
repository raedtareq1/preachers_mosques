<?php 
    session_start();
    unset($_SESSION['userPreacher']);
    header('location: login.php');
?>