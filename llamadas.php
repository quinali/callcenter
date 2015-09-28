<?php
require ('config.php');
require ('validateSession.php');

$surveyID= htmlspecialchars($_GET["surveyID"]);
$idOperador=$usuario;

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
	
</head>

<div class="container">
	

<?php


$conn = mysql_connect($dbhost, $dbuser, $dbpass);

if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}


//TOTAL DE ENCUESTAS ASIGNADAS Y PENDIENTES PARA ESTE OPERADOR
mysql_select_db($dbname);

$sqlToProcessCount ='SELECT count(1) FROM `tokens_'.$surveyID.'` WHERE `attribute_1` ='.$idOperador.' AND `completed` ="N";';
$nEncuestasPendientes = mysql_result(mysql_query( $sqlToProcessCount, $conn ),0);
mysql_close($conn);


//TOTAL DE ENCUESTAS PARA ESTE OPERADOR (NO OLVIDAR RESETEAR VIA PROCEDURE)
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname);

$sqlTotalCount ='SELECT count(1) FROM `tokens_'.$surveyID.'` WHERE `attribute_1` ="'.$idOperador.'";';
$nEncuestasTotales = mysql_result(mysql_query( $sqlTotalCount, $conn ),0);

mysql_close($conn);
?>
<div class="container">
	<header>
<?php

echo "<h1>OPERADOR: $idOperador</h1>";
echo "<h1>Encuestas: <span class='red'>$nEncuestasPendientes</span> / <span class='green'>$nEncuestasTotales</span></h1>";
echo "<a class='button' href='logout.php'>Cerrar Sesión</a>";

?>


<button class="button" onclick="goBack()">Go Back</button>
</header>

<script>function goBack() { window.history.back();
}
</script>

<?php



if($nEncuestasTotales ==0 )
{?>

<p> No posee llamadas asiganadas para esta encuesta.</p>

<?php
} else {
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname);

$sqlToken=
'select '.
'tok.firstname,tok.lastname,tok.token,tok.attribute_9,tok.attribute_2,tok.attribute_3,tok.completed,'.
' srv.`'.$surveyID.'X3X9` as CONTACT,srv.`'.$surveyID.'X3X10` as MOTIV '.
', anws.answer '.
' from tokens_'.$surveyID.' tok '.
' left join ( '.
'    select srvMax.token, max(srvMax.id) as maxid '.
'      from survey_'.$surveyID.' srvMax '.
'    group by srvMax.token) as maxIDTable  on tok.token=maxIDTable.token'.
' left join survey_'.$surveyID.' srv on maxIDTable.maxid = srv.id '.
' left join answers anws on (anws.qid=11 and srv.`'.$surveyID.'X3X11` = anws.code)'.
' where tok.attribute_1='.$idOperador. 
' order by tok.tid;';





$retval = mysql_query( $sqlToken, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
?>


<div style="margin-top: 20px;margin-left:45px; width:80%;">
<table id="encuestas">
	<tbody>
		<tr>
			<th>Nombre</th>
			<th>Teléfono 1</th>
			<th>Teléfono 2</th>
			<th>Teléfono Móvil</th>
			<th>Emitida</th>
			<th>Recuperar</th>
			<th>Encuesta</th>
		</tr>

<?PHP

while($row = mysql_fetch_assoc($retval))
	{
	echo "<tr>";
	echo "<td>{$row['firstname']} {$row['lastname']}</td>";
	echo "<td>{$row['attribute_9']}</td>";
	echo "<td>{$row['attribute_2']}</td>";
	echo "<td>{$row['attribute_3']}</td>";
	
	//Columna emitida
	if($row['completed'] =='N')
		echo "<td></td>";
	else
		echo "<td><img src='images/green-telephon-icon.png' height='32' width='32'></td>";
	
	//Columna recuperar
	if($row['CONTACT'] =='N' and $row['MOTIV'] =='A1')
		echo "<td><a href='./rellamar.php?surveyID={$surveyID}&token={$row['token']}'><img src='images/pink-telephon-icon.png' height='32' width='32'> {$row['answer']}</a> </td>";
	else
		echo "<td></td>";
	
	//Columna acceso encuesta
	if($row['completed'] =='N')
		echo "<td><a href='http://localhost/limesurvey/index.php/survey/index/sid/$surveyID/token/{$row['token']}/lang//newtest/Y'><img src='images/Users-Enter-2-icon.png' height='32' width='32'></a></td>";
	else
		echo "<td></td>";
	
	echo "</tr>";
}
?>
	</tbody>
</table>
</div>


</div>
<?php 
}
?>
