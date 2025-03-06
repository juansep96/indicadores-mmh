<?php

require_once('../PDO.php');

$datos=$_POST['datos'];
$datos=json_decode($datos,true);

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];

$ActualizarIndicador=$conexion->prepare("UPDATE indicadores SET nombre_indicador=:1,detalle_indicador=:2,url_indicador=:3,quien_indicador=:4,cuando_indicador=:5 WHERE id_indicador = :6");
$ActualizarIndicador->bindParam(':1',$datos['nombre']);
$ActualizarIndicador->bindParam(':2',$datos['detalle']);
$ActualizarIndicador->bindParam(':3',$datos['url']);
$ActualizarIndicador->bindParam(':4',$quien);
$ActualizarIndicador->bindParam(':5',$cuando);
$ActualizarIndicador->bindParam(':6',$datos['idIndicador']);
if($ActualizarIndicador->execute()){
    $detalle = "INDICADOR EDITADO. ID: " . $datos['idIndicador'];
    InsertarAuditoria($detalle,'INDICADORES',$quien);
    $res = array (
        "success"  => true,
        "id"  => $datos['idIndicador']
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $ActualizarIndicador->errorInfo()
    );
}

echo json_encode($res);


?>