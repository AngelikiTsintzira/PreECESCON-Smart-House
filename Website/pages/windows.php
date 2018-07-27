<?php
   session_start();
   if(!isset($_SESSION['username']))
   {
   	echo "<script>window.open('login.php','_self')</script>"; 
   }
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

     <title>Greecomnia - Smart House</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>      

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
               <a class="navbar-brand" href="index.php">Smart House</a>
            </div>
			<ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="../actions/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

			<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="index.php"><i class="glyphicon glyphicon-home"></i> Home</a>
                        </li>
                        <li>
                            <a href="windows.php"><i class="glyphicon glyphicon-unchecked"></i> Windows</a>
                        </li>
                        <li>
                            <a href="sockets.php"><i class="glyphicon glyphicon-sound-dolby"></i> Sockets</a>
                        </li>
                        <li>
                            <a href="lights.php"><i class="glyphicon glyphicon-lamp"></i> Lights</a>
                        </li>
                        <li>
                            <a href="smoke_detector.php"><i class="glyphicon glyphicon-fire"></i> Smoke Detector</a>
                        </li>
                    </ul>
                </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Windows Management</h1>
                   <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Lights
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Location</th>
                                            <th>State</th>
                                            <th>Submit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
											<?php 
												 $id = $_SESSION['username'] ;
												 include "../actions/zafora_connect_pdo.php";
												 $db=db_open();
												 $sql=$db->prepare("SELECT * FROM arduino WHERE id_user= :id");
												 $sql->bindParam(':id',$id );
												 $sql->execute();
												 $result = $sql->fetchAll();
												 $id_arduino = array();
												 $i = 0;
												 foreach($result as $row) 
												 {
													 $id_arduino[$i] = $row['id_arduino'];
													 $i++;
												 }
												 
												 $type = 1;	
												 for ($x = 0; $x <=$i ; $x++) 
												 {												 										 
													 $sql=$db->prepare("SELECT * FROM sensors WHERE id_arduino= :id_arduino and type=:type");
													 $sql->bindParam(':id_arduino',$id_arduino[$x] );
													 $sql->bindParam(':type',$type );
													 $sql->execute();
													 $result = $sql->fetchAll();
													 foreach($result as $row) 
													 {
														 if ($row['default_value'] == 2)
														 {
															 echo " <tr><td>".$row['id_sensor']."</td>
																	<td>".$row['location']."</td>
																	<td><label class='switch'>
																	<input type='checkbox' id='check' onchange='changeDisable()' name='check' checked>
																	<div class='slider round'></div>
																	</label> </td> 
																	<td><a href='../actions/update_window.php?id=".$row['id_sensor']."'> <button class='btn btn-primary'>Submit</button></a></td>
																	</tr>";
														 }
														 elseif ($row['default_value'] == 1)
														 {
															 echo " <tr><td>".$row['id_sensor']."</td>
																	<td>".$row['location']."</td>
																	<td><label class='switch'>
																	<input type='checkbox' id='check' onchange='changeDisable()' name='check'  >
																	<div class='slider round'></div>
																	</label> </td> 
																	<td><a href='../actions/update_window.php?id=".$row['id_sensor']."'> <button class='btn btn-primary'>Submit</button></a></td>
																	</tr>";
														 }
														 //elseif ($row['default_value'] == 0)
														 //{
															 //if($row['user_value'] == 1)
															 //{
																	 //echo " <tr><td>".$row['id_sensor']."</td>
																			//<td>".$row['location']."</td>
																			//<td><label class='switch'>
																			//<input type='checkbox' id='check' onchange='changeDisable()' name='check'  >
																			//<div class='slider round'></div>
																			//</label> </td> 
																			//<td><a href='../actions/update_window.php?id=".$row['id_sensor']."'> <button class='btn btn-primary'>Submit</button></a></td>
																			//</tr>";
															//}
															//elseif($row['user_value'] == 2)
															 //{
																	 //echo " <tr><td>".$row['id_sensor']."</td>
																			//<td>".$row['location']."</td>
																			//<td><label class='switch'>
																			//<input type='checkbox' id='check' onchange='changeDisable()' name='check'  checked>
																			//<div class='slider round'></div>
																			//</label> </td> 
																			//<td><a href='../actions/update_window.php?id=".$row['id_sensor']."'> <button class='btn btn-primary'>Submit</button></a></td>
																			//</tr>";
															//}
															//elseif($row['user_value'] == 0)
															 //{
																	 //echo " <tr><td>".$row['id_sensor']."</td>
																			//<td>".$row['location']."</td>
																			//<td><label class='switch'>
																			//<input type='checkbox' id='check' onchange='changeDisable()' name='check'  checked>
																			//<div class='slider round'></div>
																			//</label> </td> 
																			//<td><a href='../actions/update_window.php?id=".$row['id_sensor']."'> <button class='btn btn-primary'>Submit</button></a></td>
																			//</tr>";
															//}
														//}
															
													 }
											    }
											?>
                                       
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                
                <img src="../images/window.png"  style="width:300px;height:250px;" align="right">
                <!-- /.col-lg-6 -->                       
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
<br><br>
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>
