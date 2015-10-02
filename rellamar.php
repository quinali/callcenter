<?php
require ('config.php');
require ('validateSession.php');

$surveyID= htmlspecialchars($_GET["surveyID"]);
$tid=  htmlspecialchars($_GET["tid"]);

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql= 'SELECT tid,token FROM tokens_'.$surveyID.' WHERE tid="'.$tid.'" ;';
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$limeToken=$row['token'];

mysqli_close($conn);


$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
$updateSql = 'update tokens_'.$surveyID.' set completed="N" where token="'.$limeToken.'" ;';

echo "SQL=".$updateSql;



if ($conn->query($updateSql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();


//Recargarmos la página de llamadas
header("Location: llamadas.php?surveyID=".$surveyID."#tok".$tid);  

?>

