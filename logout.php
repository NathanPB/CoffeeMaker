<?php
session_start();

unset($_SESSION['usr']);
unset($_SESSION['pwd']);

header("Location: login.php");