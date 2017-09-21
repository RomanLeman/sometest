<?php

require_once("./db/zollaTables.class.php");

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$zolla->deleteRow($_GET['row_id']); 

header('Location: /');