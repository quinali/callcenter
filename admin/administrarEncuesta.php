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

	$totalOperatorsSevilla= 50;
	$totalOperatorsMadrid= 50;
	
	$surveyID= htmlspecialchars($_GET["idSurvey"]);
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	mysqli_query( $conn,"SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	//Recupera el nombre de la encuesta
	$titleSql = 'select surveyls_title from surveys_languagesettings where surveyls_survey_id ='.$surveyID;
	$result = mysqli_query($conn, $titleSql);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$title = $row["surveyls_title"];
	}
	
	
	mysqli_close($conn);
	
	
	
	
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
			echo "<h1>Encuesta: <span>$title</span></h1>" 
?>
			<a class='button' href='logout.php'>Cerrar Sesion</a>
			<a class='button' href='encuestas.php'>Go Back</a>
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
						//Propuesta operadores Sevilla
							foreach(range(1, $totalOperatorsSevilla) as $idOperador){
								if(!array_key_exists ('sev'.$idOperador,$operadores)){
											$valOperador = 'sev'.$idOperador;
											print("<option value='".$valOperador."'>Sevilla ".$idOperador."</option>");
									}
							}
							
						//Propuesta operadores Sevilla
							foreach(range(1, $totalOperatorsMadrid) as $idOperador){
								if(!array_key_exists ('mad'.$idOperador,$operadores)){
											$valOperador = 'mad'.$idOperador;
											print("<option value='".$valOperador."'>Madrid ".$idOperador."</option>");
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
				
					
					<b>Asignados: </b><br/>
					<select multiple id='lstBox2' name="lstBox2[]" size="10">
<?php

						$fieldValue = array();
			
						foreach ($operadores as $valOperator =>$nameOperador)
							{
								$fieldValue[$valOperator]=$nameOperador;
								echo "<option value='".$valOperator."'>".$nameOperador."</option>";
								echo "<script type='text/javascript'>$('input#operadoresID').val($('input#operadoresID').val()+".$valOperator.",');</script>";
							}
				
						$out = array_keys($fieldValue);
							
?>
							
								
					</select>
				</td>
			</tr>
		</table>
		<br/>
		
		<form action="guardarEncuesta.php" method="post">
					<input type="hidden" name="surveyID" value="<?php echo "$surveyID"?>"  >
					<input type="hidden" 	id="operadoresID" name="operadoresID" >
					<script type="text/javascript">$('input#operadoresID').val(<?php echo json_encode($out)?>);</script>
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
	
	function hideFieldRecharge(){
				
				fieldContent="[";
				$("#lstBox2 > option").each(function() {
					fieldContent+="'"+this.value+"',";
			});
			
			fieldContent+=']';
			fieldContent=fieldContent.replace(",]","]");

			$('input#operadoresID').val(fieldContent);
				
				
	}
	
    $(document).ready(function() {
    $('#btnRight').click(function(e) {
        var selectedOpts = $('#lstBox1 option:selected');
        if (selectedOpts.length == 0) {
            //alert("Nothing to move.");
            e.preventDefault();
        }

        $('#lstBox2').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
		hideFieldRecharge();
    });

    $('#btnLeft').click(function(e) {
        var selectedOpts = $('#lstBox2 option:selected');
        if (selectedOpts.length == 0) {
            //alert("Nothing to move.");
            e.preventDefault();
        }

        $('#lstBox1').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
		hideFieldRecharge();
    });
});
</script>
	
	


