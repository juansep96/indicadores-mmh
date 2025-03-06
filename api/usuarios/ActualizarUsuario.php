<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../PDO.php');

$datos=$_POST['datos'];
$datos=json_decode($datos,true);

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];


$ActualizarUsuario=$conexion->prepare("UPDATE usuarios SET dni_usuario=:1,apellido_usuario=:2,nombre_usuario=:3,correo_usuario=:4,telefono_usuario=:5,nivel_usuario=:7,idSecretaria_usuario=:8,idArea_usuario=:9,idIndicadores_usuario=:10 WHERE id_usuario = :6");
$ActualizarUsuario->bindParam(':1',$datos['dni']);
$ActualizarUsuario->bindParam(':2',$datos['apellido']);
$ActualizarUsuario->bindParam(':3',$datos['nombre']);
$ActualizarUsuario->bindParam(':4',$datos['correo']);
$ActualizarUsuario->bindParam(':5',$datos['telefono']);
$ActualizarUsuario->bindParam(':6',$datos['idUsuario']);
$ActualizarUsuario->bindParam(':7',$datos['nivel']);
$ActualizarUsuario->bindParam(':8',$datos['secretaria']);
$ActualizarUsuario->bindParam(':9',$datos['area']);
$ActualizarUsuario->bindParam(':10',$datos['indicadores']);
if($ActualizarUsuario->execute()){
    $detalle = "USUARIO MODIFICADO. ID: " . $conexion -> lastInsertId();
    InsertarAuditoria($detalle,'USUARIOS',$quien);
    $res = array (
        "success"  => true,
        "id"  => $conexion -> lastInsertId(),
    );
}else{
    echo "\nPDO::errorInfo():\n";
              print_r($InsertarDetalle->errorInfo());
    $res = array (
        "success"  => false,
        "error"  => $ActualizarUsuario->errorInfo()
    );
}

echo json_encode($res);


?>