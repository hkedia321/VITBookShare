<?php
include('../includes/functions.php');
session_start();
$_SESSION['login']=null;
redirect_to('index.php');
?>