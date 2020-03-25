<?php
    
   
    include "../../phps/omdb.php";
    
    $conStr = "mysql:host=" . $host. ";dbname=" . $db;
    $conexao = new PDO($conStr, $dbuser, $dbpass);
	$sql = "SELECT * FROM APPFUSIVEL ORDER BY dia DESC";
	
	$resultArray = array();
    $tempArray = array();
    
	foreach ($conexao->query($sql) as $row) {

        $date = new DateTime($row['dia']);
        
        $tempArray=array(
            "id" => $row['id'],
            "dia" =>$date->format('d-m-Y \à\s H:i:s'),
            "local" => $row['local'],
            "valor" => $row['valor'] . "A"
        );
 
        array_push($resultArray, $tempArray);
    }
    
    echo json_encode($resultArray);

?>