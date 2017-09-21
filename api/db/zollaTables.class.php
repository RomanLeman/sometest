<?php

class ZollaTablesClass{

	private $host="localhost"; 
      
    private $user="user"; 
      
    private $password="pass"; 
    
    private $db = 'zolla';

    private function connectDB(){

    }


    public function createStructure(){

 		try {
		    $conn = new PDO("mysql:host=$this->host", $this->user, $this->password);
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $sql = "CREATE DATABASE IF NOT EXISTS zolla";
		    $conn->exec($sql);
		    echo "DB created successfully\n";

		    $sql = "use zolla";
		    $conn->exec($sql);
		    
		    $sql = "CREATE TABLE IF NOT EXISTS tables (
		                ID int(11) AUTO_INCREMENT PRIMARY KEY,
		                title varchar(30) NOT NULL)";
		    $conn->exec($sql);
		    echo "Tables created successfully\n";
		    
		    $sql = "CREATE TABLE IF NOT EXISTS types ( ID int(11) AUTO_INCREMENT PRIMARY KEY, title varchar(30) NOT NULL, table_id int(11) NOT NULL)";
		    $conn->exec($sql);
		    echo "Columns types created successfully\n";
		    
		    $sql = "CREATE TABLE IF NOT EXISTS columns (ID int(11) AUTO_INCREMENT PRIMARY KEY, value varchar(50) NOT NULL, table_id int(11) NOT NULL, row_id int(11) NOT NULL, type int(11) )";
		    $conn->exec($sql);
		    echo "Columns created successfully\n";
		    
		    $sql = "CREATE TABLE IF NOT EXISTS rows (ID int(11) AUTO_INCREMENT PRIMARY KEY, color char(7) NOT NULL, table_id int(11)NOT NULL)";
		    $conn->exec($sql);
		    echo "Rows created successfully\n";
		}
		catch(PDOException $e)
		{
		    echo $sql . "<br>" . $e->getMessage();
		}
    }

    public function createTable( string $title, int $ncol = 2, int $nrow = 2){ 
    	try{
    	  $conn = new PDO("mysql:host=$this->host", $this->user, $this->password);
		  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

		  $sql = "use zolla";
		  $conn->exec($sql);			
    	  
    	  $sql = "INSERT INTO tables (title) VALUES ( '$title' )";
    	  $conn->exec($sql);

    	  $q = $conn->query("SELECT LAST_INSERT_ID()");
    	  $table_id = $q->fetchColumn();

    	  for($i=0; $i < $ncol; $i++){
    	  	$example_title = "Test_column".$i;
    	  	$sql = "INSERT INTO types (table_id, title) VALUES ('$table_id', '$example_title' ) ";
    	  	$conn->exec($sql);
    	  }

    	  for($i=0; $i < $nrow; $i++){
    	  	$sql = "INSERT INTO rows (color, table_id) VALUES ('#FFFFFF', '$table_id')";
    	  	$conn->exec($sql);
    	  	
    	  	$q = $conn->query("SELECT LAST_INSERT_ID()");
    	  	$row_id = $q->fetchColumn();

    	  	$sql = "SELECT ID FROM types WHERE table_id = $table_id";
    	  	$q = $conn->query($sql); 
    	  	$c = 0; 
    	  	foreach ($q as $row) {
    	  		
    	  		$colval = "I am a column value #".$c;
    	  		$type_id = $row['ID'];
				$sql = "INSERT INTO columns (row_id, table_id, type, value) VALUES ($row_id, $table_id, $type_id, '$colval')";  
				$conn->exec($sql);
                $c++;  	  		
    	  	}

    	  }

    	}
    	catch(PDOException $e)
		{
		    echo $sql . "<br>" . $e->getMessage();
		}

    	


    }

    public function renderTable(int $table_id = 0){
    	$table_id = intval($table_id);

    	try{
    	$conn = new PDO("mysql:host=$this->host", $this->user, $this->password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "use zolla";
		$conn->exec($sql);
    	
        /*
    	$sql = "SELECT rows.id as row_id, rows.color, types.title, columns.value FROM rows JOIN columns ON rows.id = columns.row_id JOIN types ON columns.type=types.id WHERE rows.table_id = $table_id";
        */

        $output = "";
        

        $output.="<table id='table-output' class='stack'><thead><tr><th>Color</th>";
        $sql = "SELECT * FROM types WHERE table_id = $table_id"; 
    	foreach ($conn->query($sql) as $row) {
          $output.="<th>".$row["title"]."</th>";
        }
        $output.="<th>Action</th></thead></tr>";

        //SELECT * FROM types LEFT JOIN columns ON columns.type=types.id WHERE columns.row_id=10;



        $sql = "SELECT * FROM rows WHERE table_id = $table_id";
        $q = $conn->prepare($sql); 
    	$q->execute();
        $resp = $q->fetchALL(PDO::FETCH_ASSOC); 
        $tbl = "";
        $output.="<tbody>";
        foreach ($resp as $row) {
           $output .="<tr style='background-color:".$row['color']."'>";
           $output .="<td>".$row['color']."</td>";
           $sql = "SELECT * FROM types LEFT JOIN columns ON columns.type=types.id WHERE columns.row_id =".$row['ID'];
           foreach ($conn->query($sql) as $col) {
               $output.="<td>".$col['value']."</td>";
           }
           $output .="<td><a href='/edit.php?row_id=".$row['ID']."'><i class='fi-page-edit'></i>Edit</a></td></tr>"; 
        }
        $output.="</tbody></table>";
    	//$responce = json_encode($q->fetchLL(PDO::FETCH_ASSOC)); //For ajax
        return $output;


    	}
    	catch(PDOException $e)
		{
		    echo $sql . "<br>" . $e->getMessage();
		}

    }

    public function renderTablesList(){
    	try{
    	$conn = new PDO("mysql:host=$this->host", $this->user, $this->password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "use zolla";
		$conn->exec($sql);
    	
    	$sql = "SELECT * FROM tables";

    	$q = $conn->prepare($sql); 
    	$q->execute();

    	$res = $q->fetchALL(PDO::FETCH_ASSOC);

    	$output = "";
    	
    	foreach ($res as $row) {
    		$id = $row['ID'];
    		$title = $row['title'];
    		$output .= "<option value='$id'>$title</option>";
    	}

    	return $output;

    	}
    	catch(PDOException $e)
		{
		    echo $sql . "<br>" . $e->getMessage();
		}

    }

    public function getTableData($table_id){
        $table_id = intval($table_id);
        $res = [];
        $conn = new PDO("mysql:host=$this->host", $this->user, $this->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "use zolla";
        $conn->exec($sql); 

        $sql = "SELECT * FROM types WHERE table_id = $table_id";
        $q = $conn->prepare($sql); 
        $q->execute();

        $res['types'] = $q->fetchALL(PDO::FETCH_ASSOC);

        return $res;
    }

    public function getFormData(int $row_id){
      try{

        $row_id = intval($row_id);
        $res = [];
        $conn = new PDO("mysql:host=$this->host", $this->user, $this->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "use zolla";
        $conn->exec($sql);  
        
       

        $sql = "SELECT color, table_id FROM rows WHERE id = $row_id";
        $q = $conn->prepare($sql);
        $q->execute();
        $row = $q->fetch(PDO::FETCH_ASSOC);

        $res['color'] = $row['color'];
        $res['table_id'] = $row['table_id'];
        $res['row_id'] = $row_id;

        $table_id = $row['table_id'];

        $sql = "SELECT columns.id as id, columns.value as value, types.title as title FROM types LEFT JOIN columns ON columns.type=types.id WHERE columns.row_id =".$row_id;
        $q = $conn->prepare($sql); 
        $q->execute();

        $res['columns'] = $q->fetchALL(PDO::FETCH_ASSOC);

        return $res;
        
      }  
      catch(PDOException $e)
      {
          echo $sql . "<br>" . $e->getMessage();
      }

    }

    public function newData($post){
        $conn = new PDO("mysql:host=$this->host", $this->user, $this->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "use zolla";
        $conn->exec($sql);

        $table_id = intval($_POST['table_id']);
        $color = $post['color'];
        $color = strip_tags($color);
        $color = htmlspecialchars($color);

        $sql = "INSERT INTO rows (color, table_id) VALUES ('$color', '$table_id')";
            $conn->exec($sql);

        $q = $conn->query("SELECT LAST_INSERT_ID()");
            $row_id = $q->fetchColumn();        

            foreach ($post['new_col'] as $col_id => $value) {
            
            $col_id = intval($col_id);
            $value = strip_tags($value);
            $value = htmlspecialchars($value);
            
            $sql = "INSERT INTO columns (row_id, table_id, type, value) VALUES ($row_id, $table_id, $col_id, '$value')";  
            $q = $conn->prepare($sql);
            $q->execute();


            }    

    }    

    public function saveData($post){
      try{
        $conn = new PDO("mysql:host=$this->host", $this->user, $this->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "use zolla";
        $conn->exec($sql); 

        $color = $post['color'];
        $color = strip_tags($color);
        $color = htmlspecialchars($color);
        // $color = mysql_real_escape_string($color);  // need to enable this php-module
        $row_id = intval($post['row_id']);


        $sql = "UPDATE rows SET color='$color' WHERE id = $row_id";
        $q = $conn->prepare($sql);
        $q->execute();

        foreach ($post['cols_id'] as $col_id => $value) {
            
            $col_id = intval($col_id);
            $value = strip_tags($value);
            $value = htmlspecialchars($value);
            
            $sql = "UPDATE columns SET value='$value' WHERE id = $col_id";
            $q = $conn->prepare($sql);
            $q->execute();


        }
        //header('Location: /');



      }

      catch(PDOException $e)
      {
          echo $sql . "<br>" . $e->getMessage();
      }      
    
    }

    public function deleteRow($row_id){
        try{ 
            $conn = new PDO("mysql:host=$this->host", $this->user, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "use zolla";
            $conn->exec($sql);

            $row_id = intval($row_id);

            $sql = "DELETE FROM rows WHERE id = $row_id";
            $q = $conn->prepare($sql);
            $q->execute();

            $sql = "DELETE FROM columns WHERE row_id = $row_id";
            $q = $conn->prepare($sql);
            $q->execute();

        }
        catch(PDOException $e)
        {
            echo $sql . "<br>" . $e->getMessage();
        }
    


    }    
}

$zolla = new ZollaTablesClass();

//$zolla->createStructure();

//$zolla->createTable("Test1",2,2);

//echo $zolla->renderTablesList();
