<?php
require_once("./api/db/zollaTables.class.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$table_id = intval($_GET['table_id']);
$data = $zolla->getTableData($table_id);
?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New row</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/foundation-icons.css">
    <link rel="stylesheet" href="css/app.css">
  </head>
  <body>
  <div class="top-bar">
      <div class="top-bar-left">

        <ul class="dropdown menu" data-dropdown-menu>
          <li class="menu-text">Test for </li>
          <li><a href ="/"><img src="/img/logo.png" style="height: 34px;margin-top: -12px;"></img></a></li>
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
          <h4>New row</h4>
        </div>
      </div>
      <form method="post" action="/api/newrow.php">
      <div class="grid-x grid-padding-x">
        <div class="large-2 cell">  
          <label>Color
            <input  id="clr" type="text" name="color" readonly placeholder="#FFFFFF" maxlength="7" size="7">
          </label>

        </div> 

      </div>
      <div class="grid-x grid-padding-x">
          <div class="large-4 cell"> 
            <button id="dbtn" type="button" class="button small">Default color</button>
         
         
            <button id="rbtn" type="button" class="button small">Random color</button>
          </div>
      </div> 
    
      
      <div class="grid-x grid-padding-x">
        <div class="large-12 cell"><p>Columns values:</p></div>
          
          <?php 
          foreach ($data['types'] as $col) {
          ?>
            <div class="large-5 cell">
              <label><?= $col['title']?>
                <input id="clr" type="text" name="new_col[<?=$col["ID"]?>]"  maxlength="50" size="50">
                <input type="hidden" name="table_id" value="<?= $_GET['table_id'] ?>">
              </label>
            </div>
          <?php
           }
          ?>
      
      </div>

      <div class="grid-x grid-padding-x">
          <div class="large-4 cell"> 
            <button type="submit" class="button success">Save</button>
         
          </div>
      </div> 
      </form> 

    </div>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>

    <script>
      $("#dbtn").on('click', function () {
        $('#clr').attr('value', '#FFFFFF');
        $('#clr').attr('style', 'background-color:' + '#FFFFFF');
      });

      let colorA = [
        "#e6d7f2",
        "#0f1a30",
        "#f8cc11",
        "#da251c",
        "#141a18",
        "#f0f8ff",
        "#b0e0e6",
        "#9900cc",
        "#83859a",
        "#40b0b5"
      ]

      $("#rbtn").on('click', function () {
        let ri = Math.floor(Math.random() * (10 - 1)) + 1;
        $('#clr').attr('value', colorA[ri]);
        $('#clr').attr('style', 'background-color:' + colorA[ri]);
      });



    </script>

  </body>
</html>
