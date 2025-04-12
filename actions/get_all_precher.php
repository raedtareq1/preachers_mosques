<?php
    $stmt = $conn->prepare("SELECT * FROM `preachers`");
    $stmt->execute();
    $final_data = $stmt->fetchAll();
    $prechs = [];
    foreach ($final_data as $prech) {
        $prechs [] = $prech;
    }
    return (json_encode($prechs));
?>