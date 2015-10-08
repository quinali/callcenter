<head>
	<meta charset="UTF-8" />
	<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
	<title>Listado de encuestas</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<meta name="author" content="JNL" />
	<link rel="shortcut icon" href="images/favicon.png"> 
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"-->
	
	 <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
	
	  <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
	<!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
	
	
	
</head>

<?php

require ('validateSession.php');
require ('config.php');

$idOperador=$usuario;

$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $conn);

if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}


//TOTAL DE ENCUESTAS ASIGNADAS Y PENDIENTES PARA ESTE OPERADOR
mysql_select_db($dbname);
$sqlEncuestas ="select srv.sid,srvLang.surveyls_title from surveys srv left join surveys_languagesettings srvLang on srv.sid = srvLang.surveyls_survey_id where  srv.active='Y' and (srv.expires is NULL OR srv.expires > now())";
$retval =  mysql_query( $sqlEncuestas, $conn );
mysql_close($conn);

?>
<body>
	<!-- Barra navegacion -->
	<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./encuestas.php">Operador <?php echo strtoupper ($idOperador) ?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Salir</a></li>
          </ul>
         </div>
      </div>
    </nav>

 
    <div class="container-fluid">
        
        <div class="row">
			<div class="col-sm-3 col-md-2 sidebar">
				<ul class="nav nav-sidebar">
					<li class="active"><a href="#">Encuestas activas <span class="sr-only">(current)</span></a></li>
				</ul>
				
			</div>	
			
			<!-- Page wrapper -->
			
			
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				
				<!-- breadCrum -->
				<div class="row">	
					<div class="col-lg-9">
						<ol class="breadcrumb">
							<li>
								<i class="fa fa-dashboard"></i> Encuestas
							</li>
							<li class="active">
								<i class="fa fa-comments fa-fw"></i> Encuestas activas
							</li>
						</ol>
						
						<h1 class="page-header">
							Listado <small>de encuestas activas</small>
						</h1>
					 </div>
				</div>
				<!-- ./row -->
			 <!-- ./breadCrum -->
        
			<!-- Zona del mensaje -->
            <?php if(isset($_SESSION['message'])){ ?>
				<div class="row">
					<div class="col-lg-9">
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
				<!-- /.mensaje -->
				
			<div class="row">
				<div class="col-lg-9">
					<div class="panel panel-default">
						<div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-comments fa-fw"></i> Encuestas activas</h3>
                        </div>
						
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-9 text-center">
									<div class="panel panel-default">
										<div class="panel-body">
											<div class="table-responsive">
												<table id="encuestas" class="kkk table table-bordered table-hover">
													<thead>
															<tr>
																<th>Encuesta</th>
																<th>Pendientes</th>
																<th>Totales</th>
																<th>Acceso</th>
															</tr>
														</thead>
														<tbody>
<?php if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
?>

<?PHP
while($row = mysql_fetch_assoc($retval))
	{
														echo "<tr class='alt'>";
	
	$idEncuesta = $row['sid'];
	$tituloEncuesta = $row['surveyls_title'];
														echo "<td>".$tituloEncuesta."</td>";
	
	$conn2 = mysql_connect($dbhost, $dbuser, $dbpass);
	mysql_select_db($dbname);
	
	if(! $conn2 )
		{
			die('Could not connect: ' . mysql_error());
		}

	//TOTAL DE ENCUESTAS ASIGNADAS Y PENDIENTES PARA ESTE OPERADOR
	$sqlTotales ="select ".
				" ( select count(1) from tokens_".$idEncuesta." tok where tok.completed='N' and tok.attribute_1='".$idOperador."') as pdtes,".
				" ( select count(1) from tokens_".$idEncuesta." tok WHERE tok.attribute_1='".$idOperador."') as tot;";
	
	$retval2 =  mysql_query( $sqlTotales, $conn2 );
	
	$row2 = mysql_fetch_assoc($retval2);
	
	$nTotal=$row2['tot'];
	$nPendientes=$row2['pdtes'];
	 
	
	mysql_close($conn2);
	
														echo "<td> {$nPendientes} </td>";
														echo "<td> {$nTotal} </td>";
														echo "<td>";
														if($nTotal+$nPendientes != 0)
															echo"<a href='llamadas.php?surveyID={$idEncuesta}'><i class='fa fa-sign-in fa-2x'></i></a></td>";
														echo "</td>";
	}
?>
														</tbody>
												</table>
											</div>
										</div>		
									</div>
								</div>
							</div>
							<!-- ./row -->
						</div>
					</div>
				</div>
			</div>
			<!-- /.row -->
			</div>
		
		<!-- /.row -->		
		</div>
		<!-- /page wrapper -->

    </div>
    <!-- /#wrapper -->
 

</body>

</html>				
