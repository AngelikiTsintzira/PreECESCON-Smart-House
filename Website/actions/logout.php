<?php     
	session_start();  
	session_destroy();  
	header("Location: ../pages/login.php");//redirection to login page  
?> 
