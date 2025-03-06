<?php

require_once('../PDO.php');

$datos=$_POST['datos'];
$datos=json_decode($datos,true);

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];

$InsertarArea=$conexion->prepare("INSERT INTO areas (nombre_area,idSecretaria_area,quien_area,cuando_area) VALUES (:1,:2,:3,:4)");
$InsertarArea->bindParam(':1',$datos['nombre']);
$InsertarArea->bindParam(':2',$datos['secretaria']);
$InsertarArea->bindParam(':3',$quien);
$InsertarArea->bindParam(':4',$cuando);
if($InsertarArea->execute()){
    $detalle = "NUEVA AREA CREADA. ID: " . $conexion -> lastInsertId();
    InsertarAuditoria($detalle,'AREAS',$quien);
    $res = array (
        "success"  => true,
        "id"  => $conexion -> lastInsertId(),
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $InsertarArea->errorInfo()
    );
}

echo json_encode($res);


?>