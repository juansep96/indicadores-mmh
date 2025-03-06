<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../PDO.php');

$datos=$_POST['datos'];
$datos=json_decode($datos,true);

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];

$pass = md5($datos['password']);

$InsertarUsuario=$conexion->prepare("INSERT INTO usuarios (dni_usuario,apellido_usuario,nombre_usuario,correo_usuario,telefono_usuario,contrasena_usuario,nivel_usuario,idSecretaria_usuario,idArea_usuario,idIndicadores_usuario,quien_usuario,cuando_usuario) VALUES (:1,:2,:3,:4,:5,:6,:7,:8,:9,:10,:11,:12)");
$InsertarUsuario->bindParam(':1',$datos['dni']);
$InsertarUsuario->bindParam(':2',$datos['apellido']);
$InsertarUsuario->bindParam(':3',$datos['nombre']);
$InsertarUsuario->bindParam(':4',$datos['correo']);
$InsertarUsuario->bindParam(':5',$datos['telefono']);
$InsertarUsuario->bindParam(':6',$pass);
$InsertarUsuario->bindParam(':7',$datos['nivel']);
$InsertarUsuario->bindParam(':8',$datos['secretaria']);
$InsertarUsuario->bindParam(':9',$datos['area']);
$InsertarUsuario->bindParam(':10',$datos['indicadores']);
$InsertarUsuario->bindParam(':11',$quien);
$InsertarUsuario->bindParam(':12',$cuando);
if($InsertarUsuario->execute()){
    $detalle = "NUEVO USUARIO CREADO. ID: " . $conexion -> lastInsertId();
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
        "error"  => $InsertarUsuario->errorInfo()
    );
}

echo json_encode($res);


?>