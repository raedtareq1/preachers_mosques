<?php
	include 'config.php';
    // OLD $stmt = $conn->prepare("SELECT preachers.name as prechName, mosques.name as mosqName FROM preachers INNER JOIN mosques ORDER BY RAND() LIMIT 22");
    // $stmt = $conn->prepare("SELECT DISTINCT `name` FROM preachers WHERE `name` NOT IN (SELECT name_preacher FROM `schedules` ORDER BY `schedules`.`name_preacher` ASC) ORDER BY RAND() LIMIT 22");
    $stmt = $conn->prepare("SELECT DISTINCT preachers.name AS prech_name,mosques.name AS mosq_name FROM preachers INNER JOIN mosques
                                    WHERE preachers.name NOT IN (SELECT name_preacher FROM `schedules`) 
                                    AND   mosques.name   NOT IN (SELECT name_mosque   FROM `schedules`)
                                    ORDER BY RAND() LIMIT 22");
    $stmt->execute();
    $final_data = $stmt->fetchAll();
    $prechs = [];
    foreach ($final_data as $prech) {
        $prechs [] = $prech;
    }
    print_r(json_encode($prechs));
?>