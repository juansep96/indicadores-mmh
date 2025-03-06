<?php

require_once('../PDO.php');

$datos=$_POST['datos'];
$datos=json_decode($datos,true);

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];

$InsertarMensaje=$conexion->prepare("INSERT INTO mensajes (titulo_mensaje,detalle_mensaje,quien_mensaje,cuando_mensaje) VALUES (:1,:2,:3,:4)");
$InsertarMensaje->bindParam(':1',$datos['titulo']);
$InsertarMensaje->bindParam(':2',$datos['detalle']);
$InsertarMensaje->bindParam(':3',$quien);
$InsertarMensaje->bindParam(':4',$cuando);
if($InsertarMensaje->execute()){
    $detalle = "NUEVO MENSAJE CREADO. ID: " . $conexion -> lastInsertId();
    InsertarAuditoria($detalle,'MENSAJES',$quien);
    $res = array (
        "success"  => true,
        "id"  => $conexion -> lastInsertId(),
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $InsertarMensaje->errorInfo()
    );
}

echo json_encode($res);


?>