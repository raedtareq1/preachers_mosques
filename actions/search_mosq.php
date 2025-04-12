<?php
    include '../config.php';
    $name = $_GET["name"];
    $stmt = $conn->prepare("SELECT * FROM `mosques` WHERE `name` LIKE'%$name%'");
    $stmt->execute();
    $data = $stmt->fetchall();
    print_r(json_encode($data));
?>