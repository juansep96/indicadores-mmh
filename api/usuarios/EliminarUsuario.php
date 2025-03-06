<?php

require_once('../PDO.php');

$idUsuario = $_POST['idUsuario']; 

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];
$estado = 0;

$EliminarUsuario=$conexion->prepare("UPDATE usuarios SET estado_usuario=:1 WHERE id_usuario = :2");
$EliminarUsuario->bindParam(':1',$estado);
$EliminarUsuario->bindParam(':2',$idUsuario);
if($EliminarUsuario->execute()){
    $detalle = "USUARIO ELIMINADO. ID: " . $idUsuario;
    InsertarAuditoria($detalle,'USUARIOS',$quien);
    $res = array (
        "success"  => true,
        "id"  => $idUsuario
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $EliminarUsuario->errorInfo()
    );
}

echo json_encode($res);


?>