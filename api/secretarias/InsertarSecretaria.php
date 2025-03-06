<?php

require_once('../PDO.php');

$datos=$_POST['datos'];
$datos=json_decode($datos,true);

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];

$InsertarSecretaria=$conexion->prepare("INSERT INTO secretarias (nombre_secretaria,icono_secretaria,quien_secretaria,cuando_secretaria) VALUES (:1,:2,:3,:4)");
$InsertarSecretaria->bindParam(':1',$datos['nombre']);
$InsertarSecretaria->bindParam(':2',$datos['icono']);
$InsertarSecretaria->bindParam(':3',$quien);
$InsertarSecretaria->bindParam(':4',$cuando);
if($InsertarSecretaria->execute()){
    $detalle = "NUEVA SECRETARIA CREADA. ID: " . $conexion -> lastInsertId();
    InsertarAuditoria($detalle,'SECRETARIAS',$quien);
    $res = array (
        "success"  => true,
        "id"  => $conexion -> lastInsertId(),
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $InsertarSecretaria->errorInfo()
    );
}

echo json_encode($res);


?>