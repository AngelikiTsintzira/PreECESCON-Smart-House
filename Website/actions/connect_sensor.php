<?php
include "zafora_connect_pdo.php";
$db=db_open();
$flag=0;
$ardid=$_GET['ardid'];
$id_arduino=$ardid;
$location="0";
$location=$_GET['location'];
//aisthitiras 1
$temperature=$_GET['temp'];
$humidity=$_GET['hum'];
$sensor1=$_GET['type1'];
$idsensor1=$_GET['idsensor1'];
$s1=0;
//aisthitiras 2
//$plug=$_GET['plug'];
//$light=$_GET['light'];
//$sensor2=$_GET['type2'];
//$idsensor2=$_GET['idsensor2'];
//$s2=0;
//aisthitiras 3
$socket=$_GET['socket'];
$sensor3=$_GET['sensor3'];
$idsensor3=$_GET['idsensor3'];
$s3=0;
$time=0;
//aisthitiras 4
$smoke=$_GET['smoke'];
$sensor4=$_GET['type4'];
$idsensor4=$_GET['idsensor4'];
$s4=0;
if(isset($ardid))	//ean steilei get to arduino
{
	if(isset($smoke)) //elegxos ean exei steilei timi tou smoke detector
	{
		if($smoke==1)
		{
			$view=$db->prepare('SELECT * FROM arduino WHERE id_arduino= :ardid'); 
			$view->bindParam(':ardid', $ardid);
			$view->execute();
			$result = $view->fetchAll();
			foreach($result as $row)
			{
				$id_user=$row['id_user'];
			}
			
			$view1=$db->prepare('SELECT * FROM users WHERE id_user= :id_user');
			$view1->bindParam(':id_user',$id_user);
			$view1->execute();
			$result1 = $view1->fetchAll();
			foreach($result1 as $row)
			{
				$email=$row['email'];
			}
			if(isset($email))
			{
				mail($email,'Greecomnia/Smoke Detector','Smoke has been detected!');
			}
		}
		$view=$db->prepare('SELECT * FROM sensors WHERE id_arduino= :ardid AND id_sensor= :id_sensor AND state=0 AND location= :location AND type= :sensor'); 
		$view->bindParam(':ardid', $ardid);
		$view->bindParam(':id_sensor', $idsensor4);
		$view->bindParam(':location',$location);
		$view->bindParam(':sensor',$sensor4);
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
			$insert_data->bindParam('1', $idsensor4);
			$insert_data->bindParam('2', $id_arduino);
			$insert_data->bindParam('3', $location);
			$insert_data->bindParam('4', $sensor4);
			$insert_data->execute();
		}
		$s4=$flag;
		$flag=0;
	}
	if(isset($temperature)) //ean exei dwsei timi apo ton sensor 1
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
		$view->bindParam(':id_sensor', $idsensor1);
		$view->bindParam(':location', $location);
		$view->bindParam(':sensor', $sensor1);
		$view->execute();
		$result = $view->fetchAll();
		foreach($result as $row)
		{
			$user_value=$row['user_value'];
			$default_value=$row['default_value'];
		}
		if(isset($user_value))
		{
			/*if($user_value==1 && $default_value==1)
			{
				$flag=0;
			}*/
			if ($user_value==1 && $default_value==0)
			{
				$flag=1;
			}
			elseif ($user_value==2 && $default_value==1)
			{
				$flag=2;
			}
			elseif ($user_value==2 && $default_value==0)
			{
				$flag=2;
			}
			elseif($default_value==1 && $flag==1)
			{
				$flag=0;
			}
			elseif($user_value==1 && $default_value==2)
			{
				$flag=1;
			}
			else
			{
				$flag=0;
			}
			if ($flag!=0)
			{
				
			
			//update state
			$insert_data=$db->prepare("UPDATE sensors SET state=1  WHERE id_sensor=? and id_arduino=? AND location=? AND type=?");
			$insert_data->bindParam('1', $idsensor1);
			$insert_data->bindParam('2', $id_arduino);
			$insert_data->bindParam('3', $location);
			$insert_data->bindParam('4', $sensor1);
			$insert_data->execute();
			
			$data=$db->prepare('UPDATE sensors SET state=0, default_value= :default WHERE id_sensor= :idsensor');
			$data->bindParam(':default', $flag);
			$data->bindParam(':idsensor',$idsensor1);
			$data->execute();
			}
		}
		$s1=$flag;
		$flag=0;
	}
	if(isset($plug)) //ean exei dwsei times gia ton sensor 2
	{
		if($plug==1 && $light==1)
		{
			$flag=1;
		}
		$view=$db->prepare('SELECT * FROM sensors WHERE id_arduino= :ardid AND id_sensor= :id_sensor AND location= :location AND type= :sensor AND state=0'); 
		$view->bindParam(':ardid', $ardid);
		$view->bindParam(':id_sensor', $idsensor2);
		$view->bindParam(':location',$location);
		$view->bindParam(':sensor',$sensor2);
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
			
			$data=$db->prepare('UPDATE sensors SET state=0, default_value= :default  WHERE id_sensor= :idsensor');
			$data->bindParam(':default', $flag);
			$data->bindParam(':idsensor',$idsensor2);
			$data->execute();
			
			/*$state=0;
			$data=$db->prepare('INSERT INTO sensors (id_sensor, id_arduino, default_value, state, location, type) VALUES (:idsensor, :idarduino, :default, :state, :location , :type)');
			$data->bindParam(':idsensor',$idsensor1);
			$data->bindParam(':idarduino',$id_arduino);
			$data->bindParam(':default', $flag);
			$data->bindParam(':state',$state);
			$data->bindParam(':location', $location);
			$data->bindParam(':type', $sensor1);
			$data->execute();*/
		}
		$s2=$flag;
		$flag=0;
	}
	if(isset($socket)) //elegxos ean exei orisei dedomena apo ton sensor 3
	{
		if($socket==1) //ean anoiksei kapoia mpriza
		{
			$view=$db->prepare('SELECT * FROM sensors WHERE id_arduino= :ardid AND id_sensor= :id_sensor AND state=0 AND location= :location AND type= :sensor'); 
			$view->bindParam(':ardid', $ardid);
			$view->bindParam(':id_sensor', $sensor3);
			$view->bindParam(':location', $location);
			$view->bindParam(':sensor', $idsensor3);
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
					$time=date('H:i:s');
					$view=$db->prepare('SELECT * FROM savings WHERE id_sensor= :ardid AND id_sensor= :id_sensor AND mode=1'); 
					$view->bindParam(':ardid', $sensor3);
					$view->bindParam(':id_sensor', $idsensor3);
					$view->execute();
					$result = $view->fetchAll();
					foreach($result as $row)
					{
						$timestamp=$row['timestamp'];
						
					}
					if(isset($timestamp)) //ean einai idi anoigmeno
					{
							if(strtotime('now')-strtotime($timestamp)<=400) //tha meinei anoixto
							{
								$flag=1;
							}
							else //tha kleisei
							{
								$flag=0;
							}
					}
					else //tha dimiourgisei kenourgio timestamp
					{
						$time=date('H:i:s');
						$mode = 1;
						$records = $db->prepare('UPDATE savings SET mode=:mode, timestamp=:timestamp WHERE id_sensor=:sensor');								
						$records->bindParam(':mode', $mode);
						$records->bindParam(':timestamp', $time);
						$records->bindParam(':sensor', $sensor3);
						$records->execute();
						$flag = 1;
						
					}
				}
				else
				{
					$flag=0;
				}
						$records = $db->prepare('UPDATE sensors SET default_value=:default WHERE id_sensor=:sensor');								
						$records->bindParam(':default', $flag);
						$records->bindParam(':sensor', $sensor3);
						$records->execute();
			}
		}
		$s3=$flag;
		$flag=0;
	}
	//echo '~'.$s1.$s2.$s3.$s4.'~';
	//$s3=1;
	echo '~'.$s1.$s3;
}
?>
