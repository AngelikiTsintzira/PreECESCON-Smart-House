<?php
	$id_sensor = $_GET['id'];
	include "zafora_connect_pdo.php";
	$db=db_open();
	$select_type = $db->prepare('SELECT user_value FROM sensors WHERE id_sensor = :id_sensor');
	$select_type->bindParam(':id_sensor', $id_sensor);
	$select_type->execute();
	$seltype=$select_type->fetchColumn();
	if($seltype == 0)
	{
		$value = "1"; 
	}
	elseif ($seltype == 1)
	{
		$value = "0";
	}
	
	$insert_data=$db->prepare('UPDATE sensors SET user_value= :user_value WHERE id_sensor= :id_sensor');
	$insert_data->bindParam(':user_value', $value);        
	$insert_data->bindParam(':id_sensor', $id_sensor);
	$insert_data->execute();     
	$count = $insert_data->rowCount();		
	if($count == 0)
	{
		 echo '<script>alert("An error occured with the server. Please try again!")</script>';
		 echo"<script>window.open('../pages/lights.php','_self')</script>";  
	}
	else
	{
		echo '<script>alert("Wait a few seconds for your command to be executed!!")</script>';
		echo"<script>window.open('../pages/index.php','_self')</script>"; 
	}
?>
