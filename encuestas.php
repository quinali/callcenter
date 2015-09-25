<head>
<style>
body {background-color:lightgray}
h1   {color:blue}
p    {color:green}

.red { color:red}
.green { color:green}

</style>
</head>
<?php

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'limesurvey';

$limeSurvey= htmlspecialchars($_GET["surveyID"]);
$idOperador=htmlspecialchars($_GET["idOperador"]);


$conn = mysql_connect($dbhost, $dbuser, $dbpass);

if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}



//TOTAL DE ENCUESTAS ASIGNADAS Y PENDIENTES PARA ESTE OPERADOR
mysql_select_db($dbname);

$sqlToProcessCount ='SELECT count(1) FROM `lime_tokens_'.$limeSurvey.'` WHERE `attribute_1` ='.$idOperador.' AND `completed` ="N";';
$nEncuestasPendientes = mysql_result(mysql_query( $sqlToProcessCount, $conn ),0);

mysql_close($conn);


//TOTAL DE ENCUESTAS PARA ESTE OPERADOR (NO OLVIDAR RESETEAR VIA PROCEDURE)
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname);

$sqlTotalCount ='SELECT count(1) FROM `lime_tokens_'.$limeSurvey.'` WHERE `attribute_1` ="'.$idOperador.'";';
$nEncuestasTotales = mysql_result(mysql_query( $sqlTotalCount, $conn ),0);

mysql_close($conn);


echo "<h1>OPERADOR: $idOperador</h1>";
echo "<h2>Encuestas: <span class='red'>$nEncuestasPendientes</span> / <span class='green'>$nEncuestasTotales</span></h2>";


$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname);

$sqlToken='select firstname,lastname,token,attribute_1,attribute_2,attribute_3,completed  from lime_tokens_759124 where attribute_1='.$idOperador.';';

$retval = mysql_query( $sqlToken, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
?>

<table>
	<tr>
		<th>Nombre</th>
		<th>Teléfono 1</th>
		<th>Teléfono 2</th>
		<th>Teléfono Móvil</th>
		<th>Encuesta</th>
		<th>Estado</th>
	</tr>

<?PHP

while($row = mysql_fetch_assoc($retval))
	{
	echo "<tr>";
	echo "<td>{$row['firstname']} {$row['lastname']}</td>";
	echo "<td>{$row['attribute_1']}</td>";
	echo "<td>{$row['attribute_2']}</td>";
	echo "<td>{$row['attribute_3']}</td>";
	
	if($row['completed'] =='N')
		echo "<td><a href='http://localhost/limesurvey/index.php/survey/index/sid/$limeSurvey/token/{$row['token']}/lang//newtest/Y'>Acceder</a></td>";
	else
		echo "<td>--</td>";
	
	
	if($row['completed'] =='N')
		echo "<td>No</td>";
	else
		echo "<td>Si</td>";
	echo "</tr>";
}
?>
</table>

