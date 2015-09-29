<?php 

 $valores = $_POST["tpack"];
 $surveyID= $_POST["surveyID"];
 
  print "You are selected $surveyID<br/>";
print_r($valores);
  
 foreach($valores AS $valor)
  {
     echo $valor.",";
  }
 ?>