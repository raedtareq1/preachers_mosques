<?php
	include 'config.php';
    $stmt = $conn->prepare("WITH teachers_ranked AS(SELECT *, ROW_NUMBER() OVER (ORDER BY RAND()) AS rn FROM preachers),
        students_ranked AS ( SELECT *, ROW_NUMBER() OVER (ORDER BY RAND()) AS rn FROM mosques )
        SELECT t.id AS teacher_id, t.name AS prech_name, s.id AS student_id, s.name AS mosq_name
        FROM teachers_ranked t JOIN students_ranked s ON t.rn = s.rn;");
    $stmt->execute();
    $final_data = $stmt->fetchAll();
    $prechs = [];
    foreach ($final_data as $prech) {
        $prechs [] = $prech;
    }
    print_r(json_encode($prechs));
?>