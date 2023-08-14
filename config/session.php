<?php
//session_start();
    if (isset($_SESSION['userid'])) {
        echo file_get_contents('../html/logout.html'); 
    } else {
        echo file_get_contents('../html/login.html');
    }