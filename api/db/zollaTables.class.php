<?php

class ZollaTablesClass{

	private $host="localhost"; 
      
    private $user="root"; 
      
    private $password="sarmat12"; 
    
    private $db = 'zolla';


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
    	  		$c++;
    	  		$colval = "I am a column value #".$c;
    	  		$type_id = $row['ID'];
				$sql = "INSERT INTO columns (row_id, table_id, type, value) VALUES ($row_id, $table_id, $type_id, '$colval')";  
				$conn->exec($sql);  	  		
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

        $table_id = "13";
        $output = "";
        

        $output.="<thead><tr>";
        $sql = "SELECT * FROM types WHERE table_id = $table_id"; 
    	foreach ($conn->query($sql) as $row) {
          $output.="<th>".$row["title"]."</th>";
        }
        $output.="</thead></tr>";





        $sql = "SELECT * FROM rows WHERE table_id = $table_id";
        $q = $conn->prepare($sql); 
    	$q->execute();
        $resp = $q->fetchALL(PDO::FETCH_ASSOC); 
        
        $output.="<tbody>";
        foreach ($resp as $row) {
           $output .="<tr>";
           $output .="<td>".$row['color']."</td><td>Colums place</td>";
           $output .="</tr>"; 
        }
        $output.="</tbody>";
    	//$responce = json_encode($q->fetchALL(PDO::FETCH_ASSOC)); //For ajax
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




}

$zolla = new ZollaTablesClass();

//$zolla->createStructure();

//$zolla->createTable("Test1",2,2);

//echo $zolla->renderTablesList();
