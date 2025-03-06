<?php

require_once('../PDO.php');

$datos=$_POST['datos'];
$datos=json_decode($datos,true);

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];

$ActualizarMensaje=$conexion->prepare("UPDATE mensajes SET titulo_mensaje=:1,detalle_mensaje=:2 WHERE id_mensaje = :3");
$ActualizarMensaje->bindParam(':1',$datos['titulo']);
$ActualizarMensaje->bindParam(':2',$datos['detalle']);
$ActualizarMensaje->bindParam(':3',$datos['idMensaje']);
if($ActualizarMensaje->execute()){
    $detalle = "MENSAJE EDITADO. ID: " . $datos['idMensaje'];
    InsertarAuditoria($detalle,'MENSAJES',$quien);
    $res = array (
        "success"  => true,
        "id"  => $datos['idMensjae']
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $ActualizarMensaje->errorInfo()
    );
}

echo json_encode($res);


?>