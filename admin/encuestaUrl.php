<?php
require ('../config.php');
require ('validateAdminSession.php');

$surveyID = htmlspecialchars($_GET["idSurvey"]);
$accion = htmlspecialchars($_GET["accion"]);

function getPlugginSetting($dbhost, $dbuser, $dbpass, $dbname,$surveyID){
	
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	$sql = "SELECT CONCAT( ".
			"(select qid from questions q where sid=ss.sid and title='G1P3' and (locate('Indique la causa por la que hay que volver a llamar al cliente',question)<>0 OR locate('Indique la causa por la que sea necesario volver a llamar al cliente.',question)<>0)), ".
			"',X',(select gid from groups where sid=ss.sid and group_name='G1'),'X',(select qid from questions q where sid=ss.sid and title='G1P1' and locate('¿Ha contactado con el cliente?',question)<>0), ".
			"',X',(select gid from groups where sid=ss.sid and group_name='G1'),'X',(select qid from questions q where sid=ss.sid and title='G1P2' and (locate('En caso de no haber contactado con el cliente indique la causa',question)<>0 OR locate('En caso de no haber contactado indique la causa',question)<>0)), ".
			"',X',(select gid from groups where sid=ss.sid and group_name='G1'),'X',(select qid from questions q where sid=ss.sid and title='G1P3' and (locate('Indique la causa por la que hay que volver a llamar al cliente',question)<>0 OR locate('Indique la causa por la que sea necesario volver a llamar al cliente.',question)<>0)) ".
			") as VALUE  from surveys ss WHERE ss.sid=".$surveyID;
	
	
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	//Recupera el nombre de la encuesta
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$value = $row["VALUE"];
	}
	
	mysqli_close($conn);
	
	return $value;
}


//$_SESSION['message'] = " URL generada con exito."; 	

if($accion === "url"){
	
	$page=$_SERVER['REQUEST_URI'];
	$segments=explode('/',trim($page,'/'));

	$sitePage = $segments[0];
	
	echo "http://".$_SERVER['SERVER_NAME']."/".$sitePage."/llamadas.php?surveyID=".$surveyID;
	
} else if($accion === "title"){
	echo "Listado de Usuarios";

}else if($accion === "value"){
	echo getPlugginSetting($dbhost, $dbuser, $dbpass, $dbname,$surveyID);
}




?>

