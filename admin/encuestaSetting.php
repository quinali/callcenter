<!DOCTYPE html>
<html lang="en">


	

<?php
	require ('validateAdminSession.php');
	require ('../config.php');

	$totalOperatorsSevilla= 50;
	$totalOperatorsMadrid= 50;
	
	$surveyID= htmlspecialchars($_GET["idSurvey"]);
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	mysqli_query( $conn,"SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	//Recupera el nombre de la encuesta
	$titleSql = 'select surveyls_title from surveys_languagesettings where surveyls_survey_id ='.$surveyID;
	$result = mysqli_query($conn, $titleSql);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$title = $row["surveyls_title"];
	}
	
	
	mysqli_close($conn);
	
	$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	mysql_select_db($dbname);

	$sqlOperadores ="SELECT * FROM survey_operators WHERE idSurvey=".$surveyID;

	$retval =  mysql_query( $sqlOperadores, $conn );

	$operadores = array();

	//Sacamos los operadores asignados a este encuesta
	while($row = mysql_fetch_assoc($retval))
		{
		
			$operadores [$row['idOperator']] = $row['nameOperator'];
		
		}
	mysql_close($conn);

	//print($sqlOperadores);
	//print_r($operadores);
	
	 
	
	//TOTAL DE ENCUESTAS ASIGNADAS Y PENDIENTES PARA ADMINISTRAR
	$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	mysql_select_db($dbname);

	if(! $conn )
		{
			die('Could not connect: ' . mysql_error());
		}

	$sqlTotales =	"select ".
					" ( select count(1) from (select distinct(attribute_1) from tokens_".$surveyID." tok group by attribute_1) as difOperadores ) as nOperadoresAsignados,".
					" (select count(1) from survey_operators where idSurvey=".$surveyID.") as nOperadores;";
	
	$retval2 =  mysql_query( $sqlTotales, $conn );
	
	$row2 = mysql_fetch_assoc($retval2);
	$nOperadoresAsignados=$row2['nOperadoresAsignados'];
	$nOperadores=$row2['nOperadores'];	
	
	mysql_close($conn);

?>

<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Administracion Callcenter</title>

	<script src="../js/jquery.js"></script>
	
    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="encuestas.php">Encuestas</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $usuario;?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="encuestas.php"><i class="fa fa-fw fa-gear"></i> Volver</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Salir</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="administrarEncuesta.php?idSurvey=<?php echo $surveyID; ?>"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="asignarOperadores.php?idSurvey=<?php echo $surveyID;?>"><i class="fa fa-fw fa-users"></i> Operadores</a>
                    </li>
					<li class="active">
                        <a href="#"><i class="fa fa-fw fa-pencil-square-o"></i> Configuración</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-8">
                        <h1 class="page-header">
                            <small>Configuraci&oacute;n de</small>
							<br/><?php echo $title;?>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i><a href='administrarEncuesta.php?idSurvey=<?php echo "$surveyID"?>'>Dashboard</a>
						    </li>
                            <li class="active">
                                <i class="fa fa-pencil-square-o"></i> Configuraci&oacute;n
                            </li>
                        </ol>
                    </div>
				</div>
                <!-- /.row -->
             
			 <!-- Zona del mensaje -->
			<?php if(isset($_SESSION['message'])){ ?>
				<div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            
							<?php
									$message = $_SESSION['message'];
									unset( $_SESSION['message']);
							?>
							<i class="fa fa-info-circle"></i>  <?php echo $message;?>
                        </div>
                    </div>
                </div>
				<!-- /.row -->
			<?php } ?>
			
			
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-pencil-square-o fa-fw"></i> Configuraci&oacute;n de encuestas:</h3>
                            </div>
                            <div class="panel-body">
							
									<div class="row">
										
										<label for="url" >URL language</label>
										<input type="text" name="url" id="url" class="form-control" placeholder="Introduzca su URL" value="http://localhost/encuestasv2/llamadas.php?surveyID=<?php echo $surveyID; ?>" required autofocus>
										
										<label for="urlTitle" >URL title</label>
										<input type="text" id="urlTitle" name="urlTitle" class="form-control" value="Listado de clientes" required>
										
										<label for="settings" >Pluggins settings</label>
										<input type="text" name="settings" id="settings" class="form-control" placeholder="Introduzca sus paramtros" value="138,X16X136,X16X137,X16X138" required autofocus>
										
										
																		
										
										
									</div>
									<!-- /.row -->
								
							</div>
							
							 <div class="panel-footer text-right">
                                <form action="guardarEncuesta.php" method="post">
									<input type="submit" class="btn btn-info" value="Guardar">
								</form>	
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

				
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

   

	<!-- jQuery -->
    

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

</body>

</html>


