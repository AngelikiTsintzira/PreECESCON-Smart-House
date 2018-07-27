<?php
include "connect_pdo.php";
$db=db_open();

$var=$_GET['var'];
if(isset($var))
{
$records = $db->prepare('INSERT INTO arduino (id_arduino) VALUES (:var) WHERE id_iser=1');
$records->bindParam(':var', $var);
$records->execute();
}
?>