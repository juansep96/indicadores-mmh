<?php

require_once('../PDO.php');

$idArea = $_POST['idArea']; 

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];
$estado = 0;

$EliminarArea=$conexion->prepare("UPDATE areas SET estado_area=:1 WHERE id_area = :2");
$EliminarArea->bindParam(':1',$estado);
$EliminarArea->bindParam(':2',$idArea);
if($EliminarArea->execute()){
    $detalle = "AREA ELIMINADA. ID: " . $idArea;
    InsertarAuditoria($detalle,'AREAS',$quien);
    $res = array (
        "success"  => true,
        "id"  => $idArea
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $EliminarArea->errorInfo()
    );
}

echo json_encode($res);


?>