<?php

//var_dump ($_POST);


require_once("./db/zollaTables.class.php");

//error_reporting(E_ALL);
//ini_set('display_errors', 1);


$zolla->newData($_POST); 

header('Location: /');

