<head>
	<meta charset="UTF-8" />
	<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
	<title>Listado de encuestas</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<meta name="author" content="JNL" />
	<link rel="shortcut icon" href="../favicon.ico"> 
	<link rel="stylesheet" type="text/css" href="../css/added.css" />
	<link rel="stylesheet" type="text/css" href="../css/demo.css" />
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
	<link rel="stylesheet" type="text/css" href="../css/animate-custom.css" />
</head>

<?php

require ('validateAdminSession.php');
require ('../config.php');

$surveyID= htmlspecialchars($_GET["idSurvey"]);


?>

<div class="container">
	<header>
        <h1>Administracion de <span>Encuestas Activas</span></h1>
<?php		
		echo "<h1>Encuesta: <span>$surveyID</span></h1>" 
?>
	<a class='button' href='logout.php'>Cerrar Sesion</a>
	</header>
	
	
	<div style="height:25%; background-color: #CCFF99"> ASIGNAR OPERADORES </div>
	<br/>
	<div style="height:25%; background-color: #CCFF99"> 
		<div>ASIGNAR LLAMADAS</div>
		
		<form action="reasignaEncuestas.php" method="get">
				ID Encuesta: <input "type="hidden" name="surveyID" value="<?php echo "$surveyID"?>"  >
		<br/>
			Operadores activos: <input type="text" name="nOperadores">

<br><br>
  <input type="submit" value="Submit">
</form>
	
	</div>
	
	


