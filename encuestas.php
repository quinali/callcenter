<head>
	<meta charset="UTF-8" />
	<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
	<title>Listado de encuestas</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<meta name="author" content="JNL" />
	<link rel="shortcut icon" href="../favicon.ico"> 
	<link rel="stylesheet" type="text/css" href="css/added.css" />
	<link rel="stylesheet" type="text/css" href="css/demo.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
</head>

<?php

require ('validateSession.php');
require ('config.php');

$idOperador=$usuario;

$conn = mysql_connect($dbhost, $dbuser, $dbpass);

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

<div class="container">
	<header>
        <h1>Listado de <span>Encuestas Activas</span></h1>
<?php		
		echo "<h1>Bienvenido operador <span>".strtoupper ($idOperador)."</span></h1>" 
?>
	</header>

<?PHP

echo "<a class='button' href='logout.php'>Cerrar Sesión</a>";

if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
?>
<div 
style="margin: auto;    width: 60%;    border:3px solid #8AC007;    padding: 10px;">

<table id="encuestas">
<tbody>
	<tr>
		<th>Encuestas</th>
		<th>Pendientes</th>
		<th>Totales</th>
		<th>Acceso</th>
	</tr>
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
	
	echo "<td> {$nTotal} </td>";
	echo "<td> {$nPendientes} </td>";
	
	echo "<td>";
	if($nTotal+$nPendientes != 0)
		echo"<a href='llamadas.php?surveyID={$idEncuesta}'><img src='images/Users-Enter-2-icon.png' height='32' width='32'></a></td>";
	echo "</td>";

	
	
}
?>
</tbody>
</table>
</div>