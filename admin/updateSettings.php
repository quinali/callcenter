<?php

require ('../config.php');
require ('validateAdminSession.php');

$surveyID= htmlspecialchars($_POST["idSurvey"]);

if(isset($_POST['url'])){
	 updateURL($dbhost, $dbuser, $dbpass, $dbname,$surveyID,$_POST['url']);
	$_SESSION['message'] = " URL de regreso actualizada."; 
	 header("location: encuestaSetting.php?idSurvey=".$surveyID);

}else if(isset($_POST['urlTitle'])){
	updateUrlTitle($dbhost, $dbuser, $dbpass, $dbname,$surveyID,$_POST['urlTitle']);
	$_SESSION['message'] = " Titulo de URL actualizado."; 
	header("location: encuestaSetting.php?idSurvey=".$surveyID);

}if(isset($_POST['settings'])){
	updateRellamadaSettings($dbhost, $dbuser, $dbpass, $dbname,$surveyID,$_POST['settings']);
	$_SESSION['message'] = " Parametros de rellamada actualizados."; 
	 header("location: encuestaSetting.php?idSurvey=".$surveyID);
	 
}

function updateURL($dbhost, $dbuser, $dbpass, $dbname,$surveyID,$urlValue){
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	//Actualizamos el valor
	$updateSql = "update surveys_languagesettings set surveyls_url='".$urlValue."' where surveyls_survey_id=".$surveyID;

	if (mysqli_query($conn, $updateSql)) {
  
	} else {
		echo "Error updating record: " . mysqli_error($conn);
	}

	mysqli_close($conn);
}


function updateUrlTitle($dbhost, $dbuser, $dbpass, $dbname,$surveyID,$urlTitle){
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	//Actualizamos el valor
	$updateSql = "update surveys_languagesettings set surveyls_urldescription='".$urlTitle."' where surveyls_survey_id=".$surveyID;

	if (mysqli_query($conn, $updateSql)) {
  
	} else {
		echo "Error updating record: " . mysqli_error($conn);
	}

	mysqli_close($conn);
}

function updateRellamadaSettings($dbhost, $dbuser, $dbpass, $dbname,$surveyID,$value){
	
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	//Primero validamos si existe el registro
	$result = mysqli_query( $conn,"select * from plugin_settings where model= 'mkp_recall' and `key`='".$surveyID."'");
	
	if (mysqli_num_rows($result) > 0) {
		
		mysqli_close($conn);
		$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
		//Actualizamos el valor
		$updateSql = "update plugin_settings set value='".$value."' where `key`=".$surveyID;

	if (mysqli_query($conn, $updateSql)) {
  
	} else {
		echo "Error updating record: " . mysqli_error($conn);
	}

		mysqli_close($conn);
	
	
	}else{
		
		$insertSQL= "INSERT INTO plugin_settings(plugin_id ,model ,model_id ,`key` ,value ) VALUES ( 2, 'mkp_recall', 1, ".$surveyID." ,'".$value."' )";
		 
		 if (mysqli_query($conn, $insertSQL)) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		
	}
	
	
	
	
	
	
}
?>