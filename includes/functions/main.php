<?php
    // include 'config.php';
    /*
    *** Get All Data Table: Verssion One
    */
    function get_all_data_table($table='',$where='',$value=''){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM `$table` WHERE `$where` = '$value'");
        $stmt->execute();
        $final_data = $stmt->fetch();
        return $final_data;
    }
    // /*
    // *** This Fx Work Search In Table
    // */
    function search_table(){
        global $conn;
        $stmt = $conn->prepare("SELECT schedules.*,mosques.name AS name_mosque,preachers.name AS name_preacher FROM `schedules`
                                        INNER JOIN mosques ON mosques.id = schedules.mosque_id
                                        INNER JOIN preachers ON preachers.id = schedules.preacher_id");
        $stmt->execute();
        $final_data = $stmt->fetchAll();
        return $final_data;
    }
    // /*
    // *** Print Title Page
    // */
    global $pageTitle;
    function git_title($pageTitle){
        if (isset($pageTitle)) {
            echo $pageTitle;
        }else{
            echo "Defult";
        }
    }
?>