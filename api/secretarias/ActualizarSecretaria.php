<?php

require_once('../PDO.php');

$datos=$_POST['datos'];
$datos=json_decode($datos,true);

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];

$ActualizarSecretaria=$conexion->prepare("UPDATE secretarias SET nombre_secretaria=:1,icono_secretaria=:2 WHERE id_secretaria = :3");
$ActualizarSecretaria->bindParam(':1',$datos['nombre']);
$ActualizarSecretaria->bindParam(':2',$datos['icono']);
$ActualizarSecretaria->bindParam(':3',$datos['idSecretaria']);
if($ActualizarSecretaria->execute()){
    $detalle = "SECRETARIA EDITADA. ID: " . $datos['idSecretaria'];
    InsertarAuditoria($detalle,'SECRETARIAS',$quien);
    $res = array (
        "success"  => true,
        "id"  => $datos['idSecretaria']
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $ActualizarSecretaria->errorInfo()
    );
}

echo json_encode($res);


?>