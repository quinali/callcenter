<?php

require ('validateAdminSession.php');
require ('../config.php');

$surveyID= htmlspecialchars($_GET["surveyID"]);

$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);

if(! $conn )
{  die('Could not connect: ' . mysql_error());
}

$resetSQL='UPDATE tokens_'.$surveyID.' set attribute_1 = NULL where completed="N";';

//print($resetSQL);

mysqli_query($conn, $resetSQL);


//Numero de operadores
$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
$nOperadoresSQL ="SELECT CONVERT(SUBSTRING_INDEX(nameOperator,' ',-1),UNSIGNED INTEGER) as num, suvOpe.* from survey_operators suvOpe where idSurvey=".$surveyID." order by num";
$result = mysqli_query($conn, $nOperadoresSQL);

$operadores = array();
while($row =  mysqli_fetch_assoc($result))
    {
        $operadores[] = $row;
    } 
  
//print_r($operadores);
$nOperadores = 	count($operadores);
mysqli_close($conn);


//Numero de llamadas pendientes
$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
$nLlamadasPtesSQL = 'select count(1) as num from tokens_'.$surveyID.' where completed="N";';
//print("<p/>".$nLlamadasPtesSQL);
$result = mysqli_query($conn, $nLlamadasPtesSQL);
$row = mysqli_fetch_assoc($result);
$nLlamadasPtes = $row['num'];
mysqli_close($conn);

$nLlamadasPorOperador = $nLlamadasPtes / $nOperadores;

//print_r ("<br/>nOperadores=".$nOperadores);
//print_r ("<br/>nLlamadasPtes=".$nLlamadasPtes);
//print_r ("<br/>nLlamadasPorOperador=".$nLlamadasPorOperador);


//Recorro todas los tokens que no se han llamado
$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
$nLlamadasPtesSQL = 'select * from tokens_'.$surveyID.' where completed="N" order by tid;';
$result = mysqli_query($conn, $nLlamadasPtesSQL);


$nOperador= 1;
$nToken = 1;
$idsSQLTokens="(";
$updateSQL="";
$updatesArray =[];

while($row = mysqli_fetch_assoc($result))
{
	if($nToken > ($nOperador * $nLlamadasPorOperador)){
	
		$idsSQLTokens=rtrim($idsSQLTokens, ",")."); ";
		$indOperador = ($nOperador-1);
		//echo "[".$operadores[$indOperador]['idOperator']."]<br/>".$idsSQLTokens."<br/>";
		
		$updateSQL ='UPDATE tokens_'.$surveyID.' set attribute_1="'.$operadores[$indOperador]['idOperator'].'" where tid in '.$idsSQLTokens;
		array_push($updatesArray,$updateSQL);
		
		//Volvemos a empezar la query
		$idsSQLTokens="(";
		$nOperador++;
	
	}
	
	
	$idsSQLTokens.=$row["tid"].",";
	//echo("Actualizamos el responsable de la llamada ".$row["tid"]." al operador [".$nOperador."] ".$operadores[$indOperador]['idOperator']."<br/>");
	$nToken++;
}

mysqli_close($conn);

//El ultimo operador tambien tiene que ser asignado ;-)
$idsSQLTokens=rtrim($idsSQLTokens, ",").");";
$indOperador = ($nOperador-1);

$updateSQL='UPDATE tokens_'.$surveyID.' set attribute_1="'.$operadores[$indOperador]['idOperator'].'" where tid in '.$idsSQLTokens;
array_push($updatesArray,$updateSQL);


//Tras el calculo de la asignación se lanza cada query resultante
foreach ($updatesArray as $updateQuery){

$conn2 = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
	//echo ("<br>".$updateQuery);
	
	if(! $conn2 )
	{
		die('Could not connect: ' . mysql_error());
	}
	
	if (mysqli_query($conn2, $updateQuery)) {
?>
		
<?php

	} else {
		
		echo "Error updating record: " . mysqli_error($conn2);
		
		$message="Error en la asignación de llamadas  ".mysqli_error($conn2);
		$_SESSION['message'] = $message;  
?>
		<script languaje="javascript">
			location.href = "encuestas.php";
		</script>;
    
<?php
	 }
	 
	$message=" Reasignación de tareas correctamente realizada.";
	$_SESSION['message'] = $message; 
	mysqli_close($conn2);
}


 header("location: administrarEncuesta.php?idSurvey=".$surveyID);

?>





