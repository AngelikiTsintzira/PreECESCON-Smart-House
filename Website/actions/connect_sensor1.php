<?php
include "zafora_connect_pdo.php";
$db=db_open();
$flag=0;
$temperature=$_GET['temp'];
$humidity=$_GET['hum'];
$ardid=$_GET['ardid'];
$location=$_GET['location'];
$idsensor=$_GET['idsensor'];
$sensor=1;
if(isset($ardid))
{
	if($temperature<10 || $temperature>30)
	{
		$flag=1;
	}
	if($humidity>65)
	{
		$flag=1;
	}
	$view=$db->prepare('SELECT * FROM sensors WHERE id_arduino= :ardid AND id_sensor= :id_sensor AND state=0 AND location= :location AND type= :sensor'); 
	$view->bindParam(':ardid', $ardid);
	$view->bindParam(':id_sensor', $idsensor);
	$view->bindParam(':location', $location);
	$view->bindParam(':sensor', $sensor);
	$view->execute();
	$result = $view->fetchAll();
	foreach($result as $row)
	{
		$user_value=$row['user_value'];
	}
	if(isset($user_value))
	{
		if($user_value==1)
		{
			$flag=1;
		}
		else
		{
			$flag=0;
		}
		//update state
		$insert_data=$db->prepare("UPDATE sensors SET state=1  WHERE id_sensor=? and id_arduino=? AND location=? AND type=?");
		$insert_data->bindParam('1', $idsensor);
		$insert_data->bindParam('2', $id_arduino);
		$insert_data->bindParam('3', $location);
		$insert_data->bindParam('4', $sensor);
		$insert_data->execute();
	}
	$flag=1;
	echo '~'.$flag.'~';
	
	
}
?>
