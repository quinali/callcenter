<head>
	<meta charset="UTF-8" />
	<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
	<title>Listado de encuestas</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<meta name="author" content="JNL" />
	<link rel="shortcut icon" href="../favicon.ico"> 
	<link rel="stylesheet" type="text/css" href="../css/added.css" />
	<link rel="stylesheet" type="text/css" href="../css/demo.css" />
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
	<link rel="stylesheet" type="text/css" href="../css/animate-custom.css" />
		
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"-->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>

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

<div class="container">
	<header>
        <h1>Administracion de <span>Encuestas Activas</span></h1>
<?php		
		echo "<h1>Bienvenido <span>$idOperador</span></h1>" 
?>
	</header>

	<a class='btn btn-info' href='logout.php'>Cerrar Sesión</a>
	
	
<?PHP



if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
?>
<div 
style="border:3px solid #8AC007;padding: 10px; margin-top: 20px;margin-left:45px; width:80%;">

<table id="encuestas">
<tbody>
	<tr>
		<th>Encuesta</th>
		<th>Pendientes</th>
		<th>Totales</th>
		<th>Operad.Asoc</th>
		<th>Operad.Tot</th>
		<th>Acceso</th>
	</tr>
	
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
</table>
</div>