<?php
    include_once 'env.php';

    try {
        $db = new PDO($GLOBALS['dbstring'], $GLOBALS['dbusr'], $GLOBALS['dbpwd']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec("set names utf8");
        $_GLOBALS['db'] = $db;
    } catch (Exception $ex) {
        die('Unable to connect to database. Please contact your system administrator');
    }