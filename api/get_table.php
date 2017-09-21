<?php

require_once("./db/zollaTables.class.php");

$table_id = $_GET['table_id'];


echo $zolla->renderTable($table_id);
echo "<br><div><a  href ='/new.php?table_id=".$table_id."' class='button small'>New row</a></div>";
