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
?>


<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Administracion Callcenter</title>

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

	<!-- jQuery -->
    <script src="../js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="../js/plugins/morris/raphael.min.js"></script>
    <script src="../js/plugins/morris/morris.js"></script>
    <script src="../js/plugins/morris/morris-data.js"></script>
	
	
	
	<!--script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script-->
	<!--script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script-->
	<!--script src="../morris.js"></script-->
	<!--script src="http://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.js"></script-->
	<!--script src="lib/example.js"></script-->
	<!--link rel="stylesheet" href="lib/example.css"-->
	<!--link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.css"-->
	<!--link rel="stylesheet" href="../morris.css"-->
	
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
                    <li class="active">
                        <a href="#"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="asignarOperadores.php?idSurvey=<?php echo $surveyID;?>"><i class="fa fa-fw fa-users"></i> Operadores</a>
                    </li>
					<li>
                        <a href="encuestaSetting.php?idSurvey=<?php echo $surveyID;?>"><i class="fa fa-fw fa-pencil-square-o"></i> Configuración</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            <small> Dashboard:</small> <br/><?php echo $title;?>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Dashboard
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
			<?php } 
			
			//TOTAL DE ENCUESTAS ASIGNADAS Y PENDIENTES PARA ADMINISTRAR
			$conn2 = mysql_connect($dbhost, $dbuser, $dbpass);
			mysql_select_db($dbname);

			if(! $conn2 )
				{
					die('Could not connect: ' . mysql_error());
				}

			$sqlTotales =	"select ".
							" ( select count(1) from tokens_".$surveyID." tok where tok.completed='N' ) as pdtes,".
							" ( select count(1) from tokens_".$surveyID." tok where tok.completed<>'N' ) as emitidas,".
							" ( select count(1) from tokens_".$surveyID." tok WHERE 1=1 ) as tot,".
							" ( select count(1) from (select distinct(attribute_1) from tokens_".$surveyID." tok group by attribute_1) as difOperadores ) as nOperadoresAsignados,".
							" (select count(1) from survey_operators where idSurvey=".$surveyID.") as nOperadores;";
			
			$retval2 =  mysql_query( $sqlTotales, $conn2 );
			
			$row2 = mysql_fetch_assoc($retval2);
			$nTotal=$row2['tot'];
			$nPendientes=$row2['pdtes'];
			$nEmitidas=$row2['emitidas'];
			$nOperadoresAsignados=$row2['nOperadoresAsignados'];
			$nOperadores=$row2['nOperadores'];	
			
			mysql_close($conn2);
			
			?>
                

            <div class="row">
				<div class="col-lg-12 col-md-6">	
                    <div class="col-lg-2 col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-phone fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"><?php echo $nPendientes;?></div>
                                        <div>Pendientes</div>
                                    </div>
                                </div>
                            </div>
							<div class="panel-body">
								<div> 
									<a class='btn btn-info' href='reasignaEncuestas.php?surveyID=<?php echo "$surveyID"?>'><span class="glyphicon glyphicon-refresh"></span> Asignar Llamadas</a>	
								</div>
							</div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-phone fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"><?php echo $nEmitidas;?></div>
                                        <div>Emitidas</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="panel panel-red">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-phone fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"><?php echo $nTotal;?></div>
                                        <div>Total</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					
					
					<div class="col-lg-2 col-md-6">
                        
                    </div>
					
					<div class="col-lg-2 col-md-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-users fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"><?php echo $nOperadoresAsignados;?></div>
                                        <div>Con llamadas</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					
					
                    <div class="col-lg-2 col-md-6">
                        <div class="panel panel-red">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-users fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"><?php echo $nOperadores;?></div>
                                        <div>Totales</div>
                                    </div>
                                </div>
                            </div>
                            <a href="asignarOperadores.php?idSurvey=<?php echo $surveyID;?>">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
				</div>
            </div>
            <!-- /.row -->

           <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> Llamadas por operador</h3>
                            </div>
                            <div class="panel-body">
							
<?php
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);



$sqlOperadores ="select operador, ".
"(select count(1) from tokens_".$surveyID." tk2 where tk2.attribute_1=tok1.operador and completed='N') as ptes, ".
"(select count(1) from tokens_".$surveyID." tk3 where tk3.attribute_1=tok1.operador and completed<>'N') as ejecutadas ".
"from ".
"(select distinct(attribute_1) as operador from tokens_".$surveyID."  group by attribute_1) as tok1 ";

?>

                                 <div id="graph"></div>
								 <script>
									Morris.Bar({
									  element: 'graph',
									  data: [
<?php

$result = mysqli_query($conn, $sqlOperadores);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
		echo "{x: '{$row["operador"]}', y: {$row["ptes"]},  a: {$row["ejecutadas"]}},";
    }
} 

?>							
							
                                   ],
									  xkey: 'x',
									  ykeys: ['y', 'a'],
									  labels: ['Ptes', 'Hechas'],
									  stacked: true,
									  barColors: ["#5CB85C", "#F0AD4E"],
									});
								 </script>
								 
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

  

</body>

</html>
