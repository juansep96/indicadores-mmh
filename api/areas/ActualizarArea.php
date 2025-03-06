<?php

require_once('../PDO.php');

$datos=$_POST['datos'];
$datos=json_decode($datos,true);

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];

$ActualizarArea=$conexion->prepare("UPDATE areas SET nombre_area=:1,idSecretaria_area=:2 WHERE id_area = :3");
$ActualizarArea->bindParam(':1',$datos['nombre']);
$ActualizarArea->bindParam(':2',$datos['idSecretaria']);
$ActualizarArea->bindParam(':3',$datos['idArea']);
if($ActualizarArea->execute()){
    $detalle = "AREA EDITADA. ID: " . $datos['idArea'];
    InsertarAuditoria($detalle,'AREAS',$quien);
    $res = array (
        "success"  => true,
        "id"  => $datos['idArea']
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $ActualizarSecretaria->errorInfo()
    );
}

echo json_encode($res);


?>