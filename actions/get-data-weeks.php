<?php 
    include '../config.php';
    $week = $_GET['week'];
    if (isset($_GET['week']) && !empty($_GET['week'])) {
        $stmt = $conn->prepare("SELECT mosques.name AS mosq_name,preachers.name AS prch_name ,schedules.* FROM schedules
                                        INNER JOIN preachers ON schedules.preacher_id = preachers.id
                                        INNER JOIN mosques ON schedules.mosque_id = mosques.id
                                        WHERE schedules.schd_week = $week ORDER BY preachers.name ASC");
        $stmt->execute();
        $final_data = $stmt->fetchAll();
        $prechs = [];
        foreach ($final_data as $prech) {
            $prechs [] = $prech;
        }
    }else{
        $stmt = $conn->prepare("SELECT mosques.name AS mosq_name,preachers.name AS prch_name ,schedules.* FROM schedules
                                        INNER JOIN preachers ON schedules.preacher_id = preachers.id
                                        INNER JOIN mosques ON schedules.mosque_id = mosques.id ORDER BY preachers.name ASC");
        $stmt->execute();
        $final_data = $stmt->fetchAll();
        $prechs = [];
        foreach ($final_data as $prech) {
            $prechs [] = $prech;
        }
    }
    print_r (json_encode($prechs));
?>