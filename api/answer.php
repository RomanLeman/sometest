<?php

$choose = intval($_GET['v']);

$prizr = rand(1,3);
$prizr = 3;

$res = ($choose == $prizr) ? $res = 1 : $res = 0; 
echo $res;
