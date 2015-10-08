<?php

header("Content-Type: text/html; charset=utf-8");
require ("config.php");
require ("validateSession.php");

$surveyID= htmlspecialchars($_GET["surveyID"]);
$idOperador=$usuario;
$numResultaPerPag=10;

//Encuestas del tipo segunda vuelta
$encuestasRellamada = array (376647);

//Calculamos las llamadas pendientes, recuperadas y totales
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

$sqlTotalCount ="SELECT ".
"(SELECT count(1) FROM `tokens_".$surveyID."` WHERE `attribute_1` ='".$idOperador."' and completed='N') as totalPtes,".
"(SELECT count(1) FROM `tokens_".$surveyID."` WHERE `attribute_1` ='".$idOperador."' and completed<>'N' ) as totalEmitidas,".
"(SELECT count(1) FROM `tokens_".$surveyID."` WHERE `attribute_1` ='".$idOperador."') as totalAsignadas" ;

$result = mysqli_query($conn, $sqlTotalCount);

if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$nEncuestasPendientes = $row["totalPtes"];
		$nEncuestasEmitidas = $row["totalEmitidas"];
		$nEncuestasTotales = $row["totalAsignadas"];
	}

$totalPages = ceil($nEncuestasTotales / $numResultaPerPag);

//Recupera el nombre de la encuesta
$titleSql = 'select surveyls_title from surveys_languagesettings where surveyls_survey_id ='.$surveyID;
mysqli_query( $conn,"SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$result = mysqli_query($conn, $titleSql);

	
	
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_assoc($result);
	$title = $row["surveyls_title"];
}

mysqli_close($conn);

$recallField = $_SESSION["def".$surveyID]; 

if(!isset($_GET['page'])){

	if(!isset($_SESSION['page'])){
			$_GET['page'] = 0;
	}else{
			$_GET['page'] = $_SESSION['page'];
		}		

}else{
    // Convert the page number to an integer
    $_GET['page'] = (int)$_GET['page'];
}

// If the page number is less than 1, make it 1.
if($_GET['page'] < 1){
    $_GET['page'] = 1;
    // Check that the page is below the last page
}else if($_GET['page'] > $totalPages){
    $_GET['page'] = $totalPages;
}
$page=$_GET['page'];

$_SESSION['page']=$page; 


//Procesamos la variable de session defXXXX para sacar los nombre de la columna que almacena la contestacion de rellamada
$recallConfig = explode(",",$recallField );

$anws_qid=$recallConfig[0];
$CONTACT=$recallConfig[1];
$MOTIV=$recallConfig[2];
$anws_code=$recallConfig[3];

//echo	$anws_qid.','.$CONTACT.','.$MOTIV.','.$anws_code;

?>	 
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>Listado de llamadas</title>
	<meta name="author" content="JNL" />
	<link rel="shortcut icon" href="images/favicon.png"> 
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"-->
	
	<!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
	<!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
	
	<!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
	<link href="css/sb-admin.css" rel="stylesheet">
	<link href="css/llamadas.css" rel="stylesheet">
</head>

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
					<li class="active"><a href="encuestas.php">Encuestas activas <span class="sr-only">(current)</span></a></li>
					
				</ul>
			</div>	
			
			<!-- Page wrapper -->
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				
				<!-- breadCrum -->
				<div class="row">	
				<div class="col-lg-12">
					<div class="col-lg-6">
						<ol class="breadcrumb">
							<li>
								<i class="fa fa-dashboard"></i> Encuestas
							</li>
							<li>
								<i class="fa fa-comments fa-fw"></i> Encuestas activas
							</li>
							<li class="active">
								<i class="fa fa-comments fa-fw"></i> <?php echo $title;?>
							</li>
						</ol>
						
						<h1 class="page-header">
							<small>Campaña</small> <?php echo $title;?>
						</h1>
						</div>
						<div class="col-lg-2 col-md-6">
							<div class="panel panel-green">
								<div class="panel-heading">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-phone fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge"><?php echo $nEncuestasPendientes;?></div>
											<div>Pendientes</div>
										</div>
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
											<div class="huge"><?php echo $nEncuestasEmitidas;?></div>
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
											<div class="huge"><?php echo $nEncuestasTotales;?></div>
											<div>Total asignadas</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
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
				<div class="col-lg-12">
					<div class="panel panel-default">
												
						<div class="panel-body">
							
							
<?php

$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $conn);
mysql_select_db($dbname);

$startCall = ($_GET['page'] - 1) * $numResultaPerPag;

$sqlToken=
"select ".
"tok.tid,tok.firstname,tok.lastname,tok.token,tok.attribute_2,tok.attribute_3,tok.attribute_4,tok.completed,tok.usesleft as intentos,".
" srv.`".$surveyID.$CONTACT."` as CONTACT,srv.`".$surveyID.$MOTIV."` as MOTIV ".
", anws.answer ".
" from tokens_".$surveyID." tok ".
" left join ( ".
"    select srvMax.token, max(srvMax.id) as maxid ".
"      from survey_".$surveyID." srvMax ".
"    group by srvMax.token) as maxIDTable  on tok.token=maxIDTable.token".
" left join survey_".$surveyID." srv on maxIDTable.maxid = srv.id ".
" left join answers anws on (anws.qid=".$anws_qid." and srv.`".$surveyID.$anws_code."` = anws.code)".
" where tok.attribute_1='".$idOperador."' order by tok.tid ".
" LIMIT ".$startCall.",".$numResultaPerPag;



/*if($idOperador === ('SEV4')){
	echo $idOperador."<br/>";
	echo $sqlToken;
}*/

$retval = mysql_query( $sqlToken, $conn );
if(! $retval )
{
  die("Could not get data: " . mysql_error());
}

?>						
							
							<div class="row">
								<div class="col-lg-12 text-center">
									<div class="panel panel-default">
										<div class="panel-body">
											<div class="table-responsive">
												<table id="encuestas" class="kkk table table-bordered table-hover">
													<thead>
															<tr>
																<th>Nombre</th>
																<?php if( !in_array($surveyID,$encuestasRellamada)){ ?>
			
																	<th>Teléfono 1</th>
																	<th>Teléfono 2</th>
														
																<?php } else if(in_array($surveyID,$encuestasRellamada)) {?>
			
																	<th>Fecha cita</th>
																	<th>Teléfonos</th>
																
																<?php } ?>
																<th>Recuperar</th>
																<th>Intentos</th>
																<th>Encuesta</th>
															</tr>
														</thead>
														<tbody>
<?PHP

while($row = mysql_fetch_assoc($retval))
	{
	echo "<tr id='tok".$row["tid"]."' >";
	echo "<td>{$row["firstname"]} {$row["lastname"]}</td>";
	
	//Columnas de cita y telefonos
	if( !in_array($surveyID,$encuestasRellamada)){
			
		echo "<td>{$row["attribute_2"]}</td>";
		echo "<td>{$row["attribute_3"]}</td>";
	
	} else if(in_array($surveyID,$encuestasRellamada)) {
			
		echo "<td>{$row["attribute_2"]}</td>";
		echo "<td>{$row["attribute_3"]} - {$row["attribute_4"]}</td>";	
	}
	
	//Columna recuperar
	if ($row["completed"] =="N" and (($row["CONTACT"] == "N" and $row["MOTIV"] =="A1") OR ($row["CONTACT"] == "A2" and $row["MOTIV"] =="A1"))){
		echo "<td><span class='orange'>{$row["answer"]}</span> </td>";
		
	}else if(($row["CONTACT"] =="N" and $row["MOTIV"] =="A1") OR ($row["CONTACT"] =="A2" and $row["MOTIV"] =="A1")) {
		echo "<td><a href='./rellamar.php?surveyID={$surveyID}&tid={$row["tid"]}'><span class='red! glyphicon glyphicon-refresh'></span><span class='red'>{$row["answer"]}</span></a> </td>";
	} else {	echo "<td></td>";}
	
	$nIntentos = (-1*$row["intentos"]+1);
	
	echo "<td>". (($nIntentos==0) ? " " : $nIntentos) ."</td>";
	
	//Columna acceso encuesta
	if($row["completed"] =="N")
		echo "<td><a href='/limesurvey/index.php/survey/index/sid/$surveyID/token/{$row["token"]}/lang//newtest/Y'><i class='fa fa-sign-in fa-2x'></i></a></td>";
	else
		echo "<td><span style='red' class='glyphicon glyphicon-phone-alt' data-toggle='tooltip' data-placement='top' title='{$row["completed"]}'></span></td>";
	echo "</tr>";
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
							
							<div class="row">
								<div class="col-lg-12 text-center">
									<div class="panel panel-default">
										<div class="panel-body">
											<ul class="pagination">
<?php

foreach(range(1, $totalPages) as $page){
   
	 if($page == $_GET['page']){
        echo '<li class="active"><a href="llamadas.php?surveyID='.$surveyID.'&page=' . $page . '">' . $page . '</a></li>';
    }else if($page == 1 || $page == $totalPages || ($page >= $_GET['page'] - 2 && $page <= $_GET['page'] + 2)){
        echo '<li><a href="llamadas.php?surveyID='.$surveyID.'&page=' . $page . '">' . $page . '</a></li>';
    }
}
?>
</ul>
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
		
		<!-- /.row -->		
		</div>
		<!-- /page wrapper -->

    </div>
    <!-- /#wrapper -->
	
</body>
</html>