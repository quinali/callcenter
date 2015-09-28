
<?php
require ('../config.php');

/****************************************
**establecemos conexion con el servidor.
**nombre del servidor: localhost.
**Nombre de usuario: root.
**Contraseña de usuario: root.
**Si la conexion fallara mandamos un msj 'ha fallado la conexion'**/



mysql_connect($dbhost,$dbuser,$dbpass)or die ('Ha fallado la conexión: '.mysql_error());

/*Luego hacemos la conexión a la base de datos. 
**De igual manera mandamos un msj si hay algun error*/
mysql_select_db($dbname)or die ('Error al seleccionar la Base de Datos: '.mysql_error());
 
/*caturamos nuestros datos que fueron enviados desde el formulario mediante el metodo POST
**y los almacenamos en variables.*/
$usuario = $_POST["username"];   
$password = $_POST["password"];

/*Consulta de mysql con la que indicamos que necesitamos que seleccione
**solo los campos que tenga como nombre_administrador el que el formulario
**le ha enviado*/
$result = mysql_query("SELECT * FROM users WHERE users_name = '$usuario'");

//Validamos si el nombre del administrador existe en la base de datos o es correcto
//if($row = mysql_fetch_array($result))
	if( $usuario == "admin")
{     
//Si el usuario es correcto ahora validamos su contraseña
 //if($row["password"] == $password)
   if( $password == "12345" )
 {
  //Creamos sesión
  session_start();  
  //Almacenamos el nombre de usuario en una variable de sesión usuario
  $_SESSION['usuario'] = $usuario;  
  //Redireccionamos a la pagina: index.php
  header("Location: encuestas.php");  
 }
 else
 {
  //En caso que la contraseña sea incorrecta enviamos un msj y redireccionamos a index.php
  ?>
   <script languaje="javascript">
    alert("Password Incorrecto");
    location.href = "index.php";
   </script>
  <?php
            
 }
}
else
{
 //en caso que el nombre de administrador es incorrecto enviamos un msj y redireccionamos a index.php
?>
 <script languaje="javascript">
  alert("El nombre de usuario es incorrecto!");
  location.href = "index.php";
 </script>
<?php   
        
}

//Mysql_free_result() se usa para liberar la memoria empleada al realizar una consulta
mysql_free_result($result);

/*Mysql_close() se usa para cerrar la conexión a la Base de datos y es 
**necesario hacerlo para no sobrecargar al servidor, bueno en el caso de
**programar una aplicación que tendrá muchas visitas ;) .*/
mysql_close();
?>