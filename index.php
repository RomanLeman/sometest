<?php
require_once("./api/db/zollaTables.class.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестовое задание для Zolla</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
  </head>
  <body>
    <div class="grid-container">
      <div class="grid-x grid-padding-x">
        <div class="large-12 cell">
          <h1>Тестовое задание для Zolla</h1>
        </div>
      </div>
      <div class="grid-x grid-padding-x">
        <div class="large-4 cell">  
          <label>Выбор таблицы
            <select>
              <?php
              echo $zolla->renderTablesList();
              ?>
            </select>
          </label>
        </div>
      </div>
      
      <div class="grid-x grid-padding-x">
        <div class="large-12 cell">
          <p>Вывод таблицы</p>
        </div>

        <div class="large-12 cell">
          <table class="stack">
            
           
              <?php
               echo $zolla->renderTable();
              ?>
          
          </table>
        </div>

      </div>


    </div>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>
