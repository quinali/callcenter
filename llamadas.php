<?php

header("Content-Type: text/html; charset=utf-8");
require ("config.php");
require ("validateSession.php");

$surveyID= htmlspecialchars($_GET["surveyID"]);
$idOperador=$usuario;
$numResultaPerPag=25;

//TOTAL DE ENCUESTAS PARA ESTE OPERADOR (NO OLVIDAR RESETEAR VIA PROCEDURE)
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname);

$sqlTotalCount ="SELECT count(1) FROM `tokens_".$surveyID."` WHERE `attribute_1` ='".$idOperador."';";
$nEncuestasTotales = mysql_result(mysql_query( $sqlTotalCount, $conn ),0);

$totalPages = ceil($nEncuestasTotales / $numResultaPerPag);

mysql_close($conn);



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
	<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
	<title>Listado de encuestas</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<meta name="author" content="JNL" />
	<link rel="shortcut icon" href="../favicon.ico"> 
	<link rel="stylesheet" type="text/css" href="css/added.css" />
	<link rel="stylesheet" type="text/css" href="css/demo.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
	
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"-->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	
</head>

<div class="container">
	

<?php


$conn = mysql_connect($dbhost, $dbuser, $dbpass);
 mysql_set_charset("utf8", $conn);

if(! $conn )
{
  die("Could not connect: " . mysql_error());
}


//TOTAL DE ENCUESTAS ASIGNADAS Y PENDIENTES PARA ESTE OPERADOR
mysql_select_db($dbname);

$sqlToProcessCount ="SELECT count(1) FROM `tokens_".$surveyID."` WHERE `attribute_1` ='".$idOperador."' AND `completed` ='N';";
$nEncuestasPendientes = mysql_result(mysql_query( $sqlToProcessCount, $conn ),0);
mysql_close($conn);



?>
<div class="container">
	<header>
<?php

echo "<h1>OPERADOR: $idOperador</h1>";
echo "<h1>Encuestas: <span class='red'>$nEncuestasPendientes</span> / <span class='green'>$nEncuestasTotales</span></h1>";

echo "<a class='btn btn-info' href='logout.php'>Cerrar Sesión</a>";

?>

<a class='btn btn-info' href='encuestas.php'>Volver</a>

</header>


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
<?php
	if($nEncuestasTotales ==0 )
	{?>

		<p> No posee llamadas asiganadas para esta encuesta.</p>

<?php
} else {
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $conn);

mysql_select_db($dbname);



$startCall = ($_GET['page'] - 1) * $numResultaPerPag;

$sqlToken=
"select ".
"tok.tid,tok.firstname,tok.lastname,tok.token,tok.attribute_2,tok.attribute_3,tok.completed,tok.usesleft as intentos,".
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


echo $sqlToken;

$retval = mysql_query( $sqlToken, $conn );
if(! $retval )
{
  die("Could not get data: " . mysql_error());
}
?>


<div style="margin-top: 20px;margin-left:45px; width:100%;">
<table id="encuestas">
	<tbody>
		<tr>
			<th>Nombre</th>
			<th>Teléfono 1</th>
			<th>Teléfono 2</th>
			<th>Emitida</th>
			<th>Recuperar</th>
			<th>Intentos</th>
			<th>Encuesta</th>
		</tr>

<?PHP

while($row = mysql_fetch_assoc($retval))
	{
	echo "<tr id='tok".$row["tid"]."' >";
	echo "<td>{$row["firstname"]} {$row["lastname"]}</td>";
	echo "<td>{$row["attribute_2"]}</td>";
	echo "<td>{$row["attribute_3"]}</td>";
	
	//Columna emitida
	if($row["completed"] =="N")
		echo "<td></td>";
	else
		echo "<td><span class='glyphicon glyphicon-earphone'></span></td>";
	
	//Columna recuperar
	if ($row["completed"] =="N" and $row["CONTACT"] == "N" and $row["MOTIV"] =="A1"){
		echo "<td><span class='orange'>{$row["answer"]}</span> </td>";
		
	}else if($row["CONTACT"] =="N" and $row["MOTIV"] =="A1"){
		echo "<td><a href='./rellamar.php?surveyID={$surveyID}&tid={$row["tid"]}'><span class='red glyphicon glyphicon-repeat'></span><span class='red'>{$row["answer"]}</span></a> </td>";
	} else {	echo "<td></td>";}
	
	$nIntentos = (-1*$row["intentos"]+1);
	
	echo "<td>". (($nIntentos==0) ? " " : $nIntentos) ."</td>";
	
	//Columna acceso encuesta
	if($row["completed"] =="N")
		echo "<td><a href='/limesurvey/index.php/survey/index/sid/$surveyID/token/{$row["token"]}/lang//newtest/Y'><img src='images/Users-Enter-2-icon.png' height='32' width='32'></a></td>";
	else
		echo "<td></td>";
	
	echo "</tr>";
}
?>
	</tbody>
</table>


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
<?php 
}
?>
