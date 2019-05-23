<?php
include 'includes/database.php';

session_start();
function is_authentication_valid(){
    global $db;
    if(!isset($_SESSION['usr']) || !isset($_SESSION['pwd'])){
        return false;
    } else {
        $sth = $db->prepare("select count(*) from user where usr=:usr and pwd=:pwd");
        $sth->execute($_SESSION);
        return $sth->fetchColumn();
    }
}

if(!is_authentication_valid()) {
    header("Location: login.php");
}