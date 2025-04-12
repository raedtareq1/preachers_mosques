<?php
    global $pageTitle;
    function git_title($pageTitle){
        if (isset($pageTitle)) {
            echo $pageTitle;
        }else{
            echo "Defult";
        }
    }
?>
