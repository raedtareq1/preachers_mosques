<?php 
    require_once('../config.php');
    $conn->query("DELETE FROM `schedules`");
    header('location: ../index.php');
?>