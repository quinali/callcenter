<head>
	<meta charset="UTF-8" />
	<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
	<title>Resultado de actualizacion</title>
</head>

<?php

require ('validateAdminSession.php');
require ('../config.php');

$surveyID= $_POST["surveyID"];

if (empty($_POST["operadoresID"])) 
{
?>	
	<script languaje="javascript">
		alert("No hay nada que asignar.");
		location.href = "administrarEncuesta.php?idSurvey=<?php echo $surveyID;?>";
	</script>
<?php
}else{
	$operadores = $_POST["operadoresID"];
}

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


//Borramos todos los operadores asociados a esta encuesta
$delSql = "delete from survey_operators where idSurvey =".$surveyID;

if (mysqli_query($conn, $delSql)) {
  
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}

mysqli_close($conn);


//Insetamos los operadores que nos llegan
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$sqlInsert='INSERT INTO survey_operators(idSurvey,idOperator,nameOperator) values ';

//echo $operadores."<br/>";

foreach (explode(",",$operadores) as $operador)
{	
	//echo "operador=".$operador."-->";
	$replaceString= array("[","]","'",",");
	$operador = str_replace($replaceString,"",$operador);
	//echo "--".$operador."--".substr( $operador, 0, 3)."[".substr( $operador, 3)."]<br/>";
	
	if(substr( $operador, 0, 3) ===  'sev'){
		$idOperator = substr( $operador, 3);
		$sqlInsert .= "(".$surveyID.",'sev".$idOperator."','Sevilla ".$idOperator."'),";
		//echo $sqlInsert."<br/>";
		
	}else if(substr( $operador, 0, 3) ===  'mad') {
		$idOperator = substr( $operador, 3);
		$sqlInsert .= "(".$surveyID.",'mad".$idOperator."','Madrid ".$idOperator."'),";
		
		//echo $sqlInsert."<br/>";
	}
}

//Eliminamos la ultima ,
$sqlInsert = rtrim($sqlInsert, ",");

//print($sqlInsert);
//print("<br/>");
mysqli_query($conn, $sqlInsert);

?>





<script languaje="javascript">
    alert("Operadores asignados.");
    location.href = "administrarEncuesta.php?idSurvey=<?php echo $surveyID;?>";
</script>

