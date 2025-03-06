<?php

require_once('../PDO.php');

$idMensaje = $_POST['idMensaje']; 

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];
$estado = 0;

$EliminarMensaje=$conexion->prepare("UPDATE mensajes SET estado_mensaje=:1 WHERE id_mensaje = :2");
$EliminarMensaje->bindParam(':1',$estado);
$EliminarMensaje->bindParam(':2',$idMensaje);
if($EliminarMensaje->execute()){
    $detalle = "MENSAJE ELIMINADO. ID: " . $idMensaje;
    InsertarAuditoria($detalle,'MENSAJES',$quien);
    $res = array (
        "success"  => true,
        "id"  => $idMensaje
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $EliminarMensaje->errorInfo()
    );
}

echo json_encode($res);


?>