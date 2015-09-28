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

$sqlEncuestas ="select * from surveys where  active='Y' and (expires is NULL OR expires > now())";
$retval =  mysql_query( $sqlEncuestas, $conn );
mysql_close($conn);

?>

<div class="container">
	<header>
        <h1>Listado de <span>Encuestas Activas</span></h1>
<?php		
		echo "<h1>Bienvenido operador <span>$idOperador</span></h1>" 
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
	echo "<td>{$row['sid']}</td>";
	echo "<td> - </td>";
	echo "<td> - </td>";
	
//	if($row['completed'] =='N')
		echo "<td><a href='llamadas.php?surveyID={$row['sid']}'>Acceder</a></td>";
//	else
//		echo "<td>--</td>";
	
	
}
?>
</tbody>
</table>
</div>