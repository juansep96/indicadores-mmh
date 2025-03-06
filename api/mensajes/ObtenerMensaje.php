<?php

require_once('../PDO.php');

$idMensaje = $_POST['idMensaje'];

$ObtenerMensaje = $conexion -> prepare("SELECT * FROM mensajes WHERe id_mensaje=:1");
$ObtenerMensaje -> bindParam(':1',$idMensaje);
$ObtenerMensaje -> execute();

$result = $ObtenerMensaje->fetchAll(\PDO::FETCH_ASSOC);
print_r (json_encode($result));

?>