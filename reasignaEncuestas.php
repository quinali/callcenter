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
$dbpass = 'cst@mkp1';
$dbname = 'BBDDMKP';

$surveyID= htmlspecialchars($_GET["surveyID"]);
$nOperadores=htmlspecialchars($_GET["nOperadores"]);


$conn = mysql_connect($dbhost, $dbuser, $dbpass);

if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}



//TOTAL DE ENCUESTAS ASIGNADAS Y PENDIENTES PARA ESTE OPERADOR
mysql_select_db($dbname);

$sqlToProcess ='CALL assignSurveys('.$surveyID.','.$nOperadores.');';
$result= mysql_query($sqlToProcess) or die(mysql_error()); 

mysql_close($conn);



?>

REASIGNACION REALIZADA

