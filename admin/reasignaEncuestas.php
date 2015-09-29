<?php


require ('validateAdminSession.php');
require ('../config.php');

$surveyID= htmlspecialchars($_GET["surveyID"]);

$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);


if(! $conn )
{  die('Could not connect: ' . mysql_error());
}

$resetSQL='UPDATE tokens_'.$surveyID.' set attribute_1 = NULL where completed="N";';

print($resetSQL);

mysqli_query($conn, $resetSQL);


//Numero de operadores
$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
$nOperadoresSQL = "select * from survey_operators where idSurvey=".$surveyID;
$result = mysqli_query($conn, $nOperadoresSQL);

$operadores = array();
while($row =  mysqli_fetch_assoc($result))
    {
        $operadores[] = $row;
    } 
 
  
print_r($operadores);

$nOperadores = 	count($operadores);
mysqli_close($conn);


//Numero de llamadas pendientes
$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
$nLlamadasPtesSQL = 'select count(1) as num from tokens_'.$surveyID.' where completed="N";';
print("<p/>".$nLlamadasPtesSQL);
$result = mysqli_query($conn, $nLlamadasPtesSQL);
$row = mysqli_fetch_assoc($result);
$nLlamadasPtes = $row['num'];
mysqli_close($conn);

$nLlamadasPorOperador = $nLlamadasPtes / $nOperadores;

print_r ("<br/>nOperadores=".$nOperadores);
print_r ("<br/>nLlamadasPtes=".$nLlamadasPtes."<br/>");
print_r ("<br/>nLlamadasPorOperador=".$nLlamadasPorOperador);


/*
foreach ($operadores as $operador) {
	echo "Operador es ".$operador['idOperator']."<br/>";
} */

//Recorro todas los tokens que no se han llamado
$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
$nLlamadasPtesSQL = 'select * from tokens_'.$surveyID.' where completed="N" order by tid;';
$result = mysqli_query($conn, $nLlamadasPtesSQL);


$nOperador= 1;
$nToken = 1;

while($row = mysqli_fetch_assoc($result))
{
	if($nToken < ($nOperador * $nLlamadasPorOperador)){
		$nOperador++;
	}
	
	echo($nToken."-->".$nOperador."--".$operadores[$nOperador]['idOperator']."<br/>");
	
	
	$nToken++;
}

?>


<script languaje="javascript">
    alert("REASIGNACION  DE LA ENCUESTA <?php echo "$surveyID"; ?> REALIZADA ");
    location.href = "encuestas.php";
   </script>



