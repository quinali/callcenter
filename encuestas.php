<?php
//creamos la sesion
session_start();

$idOperador = $_SESSION['usuario'];   


//validamos si se ha hecho o no el inicio de sesion correctamente
//si no se ha hecho la sesion nos regresará a index.php
if(!isset($idOperador)) 
{
  header('Location: index.php'); 
  exit();
}
?>

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

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'limesurvey';

$conn = mysql_connect($dbhost, $dbuser, $dbpass);

if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}



//TOTAL DE ENCUESTAS ASIGNADAS Y PENDIENTES PARA ESTE OPERADOR
mysql_select_db($dbname);

$sqlEncuestas ="select * from lime_surveys where  active='Y' and (expires is NULL OR expires > now())";
$retval =  mysql_query( $sqlEncuestas, $conn );
mysql_close($conn);

echo "<h1> BIENVENIDO OPERADOR: $idOperador</h1>";
echo "<a class='button' href='logout.php'>Cerrar Sesión</a>";

if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
?>

<table>
	<tr>
		<th>ENCUESTAS ACTIVAS</th>
	</tr>

<?PHP

while($row = mysql_fetch_assoc($retval))
	{
	echo "<tr>";
	echo "<td>{$row['sid']}</td>";
	
//	if($row['completed'] =='N')
		echo "<td><a href='llamadas.php?surveyID={$row['sid']}'>Acceder</a></td>";
//	else
//		echo "<td>--</td>";
	
	
}
?>
</table>

