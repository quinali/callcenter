<!DOCTYPE html>
<?php
	require ('validateAdminSession.php');
	require ('../config.php');
	
	$idOperador=$usuario;

	$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $conn);

	if(! $conn )
	{
	  die('Could not connect: ' . mysql_error());
	}


	//TOTAL DE ENCUESTAS ASIGNADAS Y PENDIENTES PARA ADMINISTRAR
	mysql_select_db($dbname);

	$sqlEncuestas ="select srv.sid,srvLang.surveyls_title from surveys srv left join surveys_languagesettings srvLang on srv.sid = srvLang.surveyls_survey_id";
	$retval =  mysql_query( $sqlEncuestas, $conn );
	mysql_close($conn);

?>

<html lang="en">
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
                <a class="navbar-brand" href="encuestas.php">SB Admin</a>
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
                        <a href="#"><i class="fa fa-fw fa-dashboard"></i> Encuestas</a>
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
                            Administración <small>encuestas activas:</small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Encuestas
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
                <!-- /.row -->

				
				<div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-comments fa-fw"></i> Encuestas activas</h3>
                            </div>
                            <div class="panel-body">
									<div class="row">
										<div class="col-lg-12 text-center">
											<div class="panel panel-default">
												<div class="panel-body">
													<div class="table-responsive">
														<table id="encuestas" class="kkk table table-bordered table-hover">
															<thead>
																<tr>
																	<th>Encuesta</th>
																	<th>Pendientes</th>
																	<th>Totales</th>
																	<th>Operad.Asoc</th>
																	<th>Operad.Tot</th>
																	<th>Acceso</th>
																</tr>
															</thead>
															<tbody>
<?PHP

														while($row = mysql_fetch_assoc($retval))
														{
																echo "<tr class='alt'>";
																$idEncuesta = $row['sid'];
																$titleEncuesta = $row['surveyls_title'];
																echo "<td>".$titleEncuesta."</td>";
																
																//TOTAL DE ENCUESTAS ASIGNADAS Y PENDIENTES PARA ADMINISTRAR
																$conn2 = mysql_connect($dbhost, $dbuser, $dbpass);
																mysql_select_db($dbname);
																
																if(! $conn2 )
																	{
																		die('Could not connect: ' . mysql_error());
																	}

																$sqlTotales ="select ".
																			" ( select count(1) from tokens_".$idEncuesta." tok where tok.completed='N' ) as pdtes,".
																			" ( select count(1) from tokens_".$idEncuesta." tok WHERE 1=1 ) as tot,".
																			" ( select count(1) from (select distinct(attribute_1) from tokens_".$idEncuesta." tok group by attribute_1) as difOperadores ) as nOperadoresAsignados,".
																			" (select count(1) from survey_operators where idSurvey=".$idEncuesta.") as nOperadores;";
																
																$retval2 =  mysql_query( $sqlTotales, $conn2 );
																
																//Solo mostramos enlace de entrada si existe la tabla de tokens
																if( $retval2){
																
																	$row2 = mysql_fetch_assoc($retval2);
																	$nTotal=$row2['tot'];
																	$nPendientes=$row2['pdtes'];
																	$nOperadoresAsignados=$row2['nOperadoresAsignados'];
																	$nOperadores=$row2['nOperadores'];	
																	
																	mysql_close($conn2);
																	
																	echo "<td> {$nTotal} </td>";
																	echo "<td> {$nPendientes} </td>";
																	echo "<td> {$nOperadoresAsignados} </td>";
																	echo "<td> {$nOperadores} </td>";
																	echo "<td><a href='administrarEncuesta.php?idSurvey={$row['sid']}'><img src='../images/Users-Enter-2-icon.png' height='32' width='32'></a></td>";
																}else{
																	
																	echo "<td>--</td>";
																	echo "<td>--</td>";
																	echo "<td>--</td>";
																	echo "<td>--</td>";
																	echo "<td></td>";
																	
																}
																
																
																 
																
																
																
																
														}
?>
															</tbody>
													
												</div>
											</div>
										</div>
									</div>	
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
    <script src="../js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

</body>

</html>
