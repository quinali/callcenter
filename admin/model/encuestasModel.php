<?php

function getTitle($dbhost,$dbuser,$dbpass,$dbname,$surveyID)
{
	$conn = getConnection ($dbhost,$dbuser,$dbpass,$dbname);
	mysqli_query( $conn,"SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	
	//Recupera el nombre de la encuesta
	$titleSql = 'select surveyls_title from surveys_languagesettings where surveyls_survey_id ='.$surveyID;
	$result = mysqli_query($conn, $titleSql);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$title = $row["surveyls_title"];
	}
		
	mysqli_close($conn);

	return $title;
}


function getUrlSurvey($dbhost,$dbuser,$dbpass,$dbname,$surveyID){
	
	$conn = getConnection ($dbhost,$dbuser,$dbpass,$dbname);
	
	$result = mysqli_query($conn, 'select surveyls_url from surveys_languagesettings where surveyls_survey_id='.$surveyID);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$surveyls_url = $row["surveyls_url"];
	}
	
	mysqli_close($conn);

	return $surveyls_url;
}


function getUrlTitle($dbhost,$dbuser,$dbpass,$dbname,$surveyID){
	
	$conn = getConnection ($dbhost,$dbuser,$dbpass,$dbname);
	$result = mysqli_query($conn, 'select surveyls_urldescription from surveys_languagesettings where surveyls_survey_id='.$surveyID);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$surveyls_urldescription = $row["surveyls_urldescription"];
	}
	
	mysqli_close($conn);

	return $surveyls_urldescription;
}


function getPlugginSetting($dbhost,$dbuser,$dbpass,$dbname,$surveyID){
	
	$conn = getConnection ($dbhost,$dbuser,$dbpass,$dbname);
	$result = mysqli_query($conn, 'select value from plugin_settings where `key`='.$surveyID);
	
	$row = mysqli_fetch_assoc($result);
	
	return $row["value"];
}

function getConnection ($dbhost,$dbuser,$dbpass,$dbname){
	
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	return $conn;
	
	
}

?>