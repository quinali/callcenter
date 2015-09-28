<?php

//creamos la sesion
session_start();

$usuario = $_SESSION['usuario'];   


//validamos si se ha hecho o no el inicio de sesion correctamente
//si no se ha hecho la sesion nos regresará a index.php
if(!isset($usuario)) 
{
  header('Location: index.php'); 
  exit();
}
