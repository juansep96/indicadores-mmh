<?php

require_once('../PDO.php');

$pass = md5($_POST['pass']);
$idUsuario = $_POST['idUsuario'];
$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];


$ActualizarUsuario=$conexion->prepare("UPDATE usuarios SET contrasena_usuario=:1 WHERE id_usuario = :2");
$ActualizarUsuario->bindParam(':1',$pass);
$ActualizarUsuario->bindParam(':2',$idUsuario);
if($ActualizarUsuario->execute()){
    $detalle = "CONTRASEÑA RESTAURADA. ID: " . $idUsuario;
    InsertarAuditoria($detalle,'USUARIOS',$quien);
    $res = array (
        "success"  => true,
        "id"  => $idUsuario
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $ActualizarUsuario->errorInfo()
    );
}

echo json_encode($res);

?>