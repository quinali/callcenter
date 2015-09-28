<?php


require ('validateAdminSession.php');
require ('../config.php');

$surveyID= htmlspecialchars($_GET["surveyID"]);
$nOperadores=htmlspecialchars($_GET["nOperadores"]);


$conn = mysql_connect($dbhost, $dbuser, $dbpass);

if(! $conn )
{  die('Could not connect: ' . mysql_error());
}



//TOTAL DE ENCUESTAS ASIGNADAS Y PENDIENTES PARA ESTE OPERADOR
mysql_select_db($dbname);

$sqlToProcess ='CALL assignSurveys('.$surveyID.','.$nOperadores.');';
$result= mysql_query($sqlToProcess) or die(mysql_error()); 

mysql_close($conn);



?>


<script languaje="javascript">
    alert("REASIGNACION  DE LA ENCUESTA <?php echo "$surveyID"; ?> REALIZADA ");
    location.href = "encuestas.php";
   </script>



