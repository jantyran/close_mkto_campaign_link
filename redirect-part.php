<?php

    include_once './reception.php';
    
    if(isset($_POST["link"])){
        $link = $_POST["link"];
    }
    if(isset($_POST["maxNum"])){
        $maxNum = intval($_POST["maxNum"]);
    }
    if(isset($_POST["programId"])){
        $programId = intval($_POST["programId"]);
    }
    
    redirect_to_form ($link, $maxNum, $programId);
    exit;