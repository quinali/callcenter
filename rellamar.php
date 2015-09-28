<?php
require ('config.php');
require ('validateSession.php');

$surveyID= htmlspecialchars($_GET["surveyID"]);
$limeToken=  htmlspecialchars($_GET["token"]);

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$updateSql = 'update tokens_'.$surveyID.' set completed="N" where token="'.$limeToken.'" ;';


echo "SQL=".$updateSql;



if ($conn->query($updateSql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();


//Recargarmos la página de llamadas
header("Location: llamadas.php?surveyID=".$surveyID);  

?>

