<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("./api/db/zollaTables.class.php");


?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test for Zolla</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/foundation-icons.css">
    <link rel="stylesheet" href="css/app.css">
  </head>
  <body>
    <div class="top-bar">
      <div class="top-bar-left">

        <ul class="dropdown menu" data-dropdown-menu>
          <li class="menu-text">Test for </li>
          <li><a href ="/"> <img src="/img/logo.png" style="height: 34px;margin-top: -12px;"></img></a></li>
           <li>
            <a href="/">Test #1</a>
            
          </li>
          <li><a href="/task2.php">Test #2</a></li>
          <li><a href="/task3.php">Test #3</a></li>
        </ul>
      </div>
    </div>



    <div class="grid-container">
      <div class="grid-x grid-padding-x">
       	<div class="large-12 cell">
          	<br>
          	<h4>Input text</h4>
        </div>
        <div class="large-5 cell">
          <textarea id="txt" rows="10" cols="100" name="text"></textarea>
        </div>

        <div class="large-12 cell">
          <button id="btntxt" class="button">Parse</button>
        </div>

        <div id="rslt" class="large-12 cell">
         <p></p>
        </div>




      </div>

     


    </div>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>

    <script>

  		 $("#btntxt").on('click', function () {
  		 		$("#rslt").html("<h4> parsed dates:</h4>");
  		 		var str = $('#txt').val();
  		 		var regexp = /[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]/gi;
  		 		var matches = str.match(regexp);
  		 		//var matches = str.match(regexp);
        		matches.forEach(function(value){
        			let date = new Date(value);
        			let unix = Date.parse(value);
        			$("#rslt").append("<p>Date:" + value + " </p><p> UNIXTIME:"  + unix + "</p><br>");
        		});
       		
     	 });
  		

    </script>
  </body>
</html>
