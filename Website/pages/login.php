<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="">
      <meta name="author" content="Dashboard">
      <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
      <title>Greecomnia - Smart House</title>
      <!-- Bootstrap core CSS -->
      <link href="assets_login/css/bootstrap.css" rel="stylesheet">
      <!--external css-->
      <link href="assets_login/font-awesome/css/font-awesome.css" rel="stylesheet" />
      <!-- Custom styles for this template -->
      <link href="assets_login/css/style.css" rel="stylesheet">
      <link href="assets_login/css/style-responsive.css" rel="stylesheet">
      <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <script src="sweetalert-master/dist/sweetalert.min.js"></script>
      <link rel="stylesheet" type="text/css" href="sweetalert-master/dist/sweetalert.css">
   </head>
   <body style="background-color:  #A9E2F3;">
   <?php
   session_start();
   if (isset($_POST['submit']))
   {
		include "../actions/zafora_connect_pdo.php";
		$db=db_open();
		$name=$_POST['username'];
		$password= $_POST['password'];           
		$records = $db->prepare('SELECT * FROM  users WHERE username = :username and password=:password');
		$records->bindParam(':username', $name);
		$records->bindParam(':password', $password);
		$records->execute();
		$results = $records->fetch(PDO::FETCH_ASSOC);
		if($records->rowCount()  > 0 )
		{
			$_SESSION['username'] = $results['id_user'];
			$_SESSION['email'] = $results['email'];
			header('location:index.php');
			exit();
		}
		else
		{
			echo '<script>alert("Username or Password is incorrect! Please try again!")</script>';
		}
   }
   
   if(isset($_POST['forget']))
   {
    include "../actions/zafora_connect_pdo.php";
   	$db=db_open();
   	$email=$_POST['user'];
   	if (empty($email))
   	{
   		echo '<script>alert("Please fill the gaps!")</script>';
   	}
   	$records = $db->prepare('SELECT * FROM  users WHERE username = :username');
   	$records->bindParam(':username', $email);
   	$records->execute();
   	$results = $records->fetch(PDO::FETCH_ASSOC);
   	if($records->rowCount()  > 0 && !empty($email) )
   	{
   		$_SESSION['email'] = $results['email'];
   		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
   		$new_password=substr(str_shuffle($chars),0,10);
   		$insert_data=$db->prepare('UPDATE users SET password=:password WHERE username=:username');
   		$insert_data->bindParam(':password', $new_password);        
   		$insert_data->bindParam(':username', $email);
   		$insert_data->execute();
   		$email = $_SESSION['email'] ;
   		
   		$message = "Your new password is :". $new_password;
   		mail($email, 'Message from remote control website', $message);
   		
   		echo '<script>swal("Check your email for your password restore!")</script>';			
   		echo"<script>window.open('login.php','_self')</script>";  
   	}
   	else if (!empty($email))
   	{
		echo '<script>swal("The username that you\'ve entered doesn\'t match any account!!")</script>';
   		//echo '<script>alert("The email address that you\'ve entered doesn\'t match any account!")</script>';
   	}
   }
   ?>
      <!-- **********************************************************************************************************************************************************
         MAIN CONTENT
         *********************************************************************************************************************************************************** -->
      <div id="login-page">
         <div class="container">
            <form class="form-login" action="login.php" method="post">
               <h2 class="form-login-heading" style= "background: #01A9DB;">Sign in now</h2>
               <div class="login-wrap">
                  <input type="text" class="form-control" placeholder="Username" name ="username" autofocus required>
                  <br>
                  <input type="password" class="form-control" placeholder="Password" name="password" required>
                  <label class="checkbox">
                  <span class="pull-right">
                  <a data-toggle="modal" href="login.php#myModal"> Forgot Password?</a>
                  </span>
                  </label>
                  <button class="btn btn-theme btn-block" type="submit" name="submit" style= "background: #01A9DB;"><i class="fa fa-lock"></i> Sign in</button>
                  <hr>
                  
               </div>
            </form>
            <!-- Modal -->	         
            <form class="form-login" action="login.php" method="post">
            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header" style= "background: #01A9DB;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Forgot Password ?</h4>
                     </div>
                     <div class="modal-body">
                        <p>Enter your username and an e-mail will be sent to you to reset your password.</p>
                        <input type="text" name="user" placeholder="Username" autocomplete="off" class="form-control placeholder-no-fix">
                     </div>
                     <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                        <button class="btn btn-theme" type="submit" name="forget" style= "background: #01A9DB;">Submit</button>
                     </div>
                  </div>
               </div>
            </div>
            <!-- modal -->
         </div>
      </div>
      <!-- js placed at the end of the document so the pages load faster -->
      <script src="assets_login/js/jquery.js"></script>
      <script src="assets_login/js/bootstrap.min.js"></script>
      <!--BACKSTRETCH-->
      <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
      <!--<script type="text/javascript" src="assets/js/jquery.backstretch.min.js"></script> -->
      <script>
         $.backstretch("assets_login/img/login-bg.jpg", {speed: 500});
      </script>
   </body>
</html>
