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

	<script src="../js/jquery-1.11.3.min.js" type="text/javascript"></script>
	
	</head>

<?php

require ('validateAdminSession.php');
require ('../config.php');

$surveyID= htmlspecialchars($_GET["idSurvey"]);


$conn = mysql_connect($dbhost, $dbuser, $dbpass);

if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}


mysql_select_db($dbname);

$sqlOperadores ="SELECT * FROM survey_operators WHERE idSurvey=".$surveyID;




$retval =  mysql_query( $sqlOperadores, $conn );

$operadores = array();

//Sacamos los operadores asignados a este encuesta
while($row = mysql_fetch_assoc($retval))
	{
	
		$operadores [$row['idOperator']] = $row['nameOperator'];
	
	}
mysql_close($conn);

//print($sqlOperadores);
//print_r($operadores);

?>


<div class="container">
	<header>
        <h1>Administracion de <span>Encuestas Activas</span></h1>
<?php		
		echo "<h1>Encuesta: <span>$surveyID</span></h1>" 
?>
	<a class='button' href='logout.php'>Cerrar Sesion</a>
	</header>
	
	
	<div style="height:50%; background-color: #CCFF99"> 
	ASIGNAR OPERADORES
	<br/>

	<div align="center">
		<table style='width:370px;'>
			<tr>
				<td style='width:160px;vertical-align:bottom;'">
					<div align="right">
						<b>No asignados:</b><br/>
					   <select multiple id='lstBox1' size="10">
					   <?php
							for ($i = 1; $i <= 50; $i++) {
								echo $i;
								
								if(!array_key_exists ('sev'.$i,$operadores)){
										print("<option value='sev".$i."'>Sevilla ".$i."</option>");
								}
								
							}
					   ?>
						  
						</select>
					</div>
				</td>
				<td style='width:50px;text-align:center;vertical-align:middle;'>
					<input type='button' id='btnRight' value ='  >  '/>
					<br/><input type='button' id='btnLeft' value ='  <  '/>
				</td>
				<td style='width:160px;'>
				<form action="guardarEncuesta.php" method="post">
					<input type="hidden" name="surveyID" value="<?php echo "$surveyID"?>"  >
					
					<b>Asignados: </b><br/>
					<select multiple id='lstBox2' name="lstBox2[]" size="10">
					<?php
							foreach ($operadores as $operador)
							{
								
								$replaceString= array("sev","Sevilla ");
								$idOperator = str_replace($replaceString,"",$operador);
								echo "<option value='sev".$idOperator."'>Sevilla ".$idOperator."</option>";
							}
					?>
		</select>
					
					<script>
						function displayVals() {
							var multipleValues = $( "#multiple" ).val() || [];
							$( "p" ).html( "<b>Single:</b> " + singleValues +
							" <b>Multiple:</b> " + multipleValues.join( ", " ) );
						}
	
						$( "select" ).change( displayVals );
						displayVals();
					</script>
 
					
					
				
				</td>
			</tr>
		</table>
		<br/>
		<input type="submit" value="Guardar">
		</form>	
	</div>
 	
	<br/>
	
	<div style="height:25%; background-color: #CCFF99"> 
		<div>ASIGNAR LLAMADAS</div>
		<br/>
		<a class='button' href='reasignaEncuestas.php?surveyID=<?php echo "$surveyID"?>'>Asignar Llamadas</a>	
				


	
	</div>
	
	<script type="text/javascript">
    $(document).ready(function() {
    $('#btnRight').click(function(e) {
        var selectedOpts = $('#lstBox1 option:selected');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }

        $('#lstBox2').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });

    $('#btnLeft').click(function(e) {
        var selectedOpts = $('#lstBox2 option:selected');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }

        $('#lstBox1').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });
});
</script>
	
	


