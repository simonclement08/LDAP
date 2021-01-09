<?php

if (isset($_GET['action'])){
    if ($_GET['action'] == 'admin'){
        if (isset($_GET['id']) and isset($_GET['password'])){
            if($_GET['id'] == "admin" and $_GET['password'] == "admin"){
                require 'controller/adminController.php';
            }
            else{
                require 'controller/homepageController.php';
            }
        }
        else{
            require 'controller/homepageController.php';
        }
    }
    elseif ($_GET['action'] == 'register'){
        require 'controller/registerController.php';
    }
    elseif ($_GET['action'] == 'condition'){
        require 'controller/conditionController.php';
    }
}
else {
    require 'controller/homepageController.php';
}
