<?php

require_once('../PDO.php');

$datos=$_POST['datos'];
$datos=json_decode($datos,true);
$datos['arrayAreas'] = json_decode($datos['arrayAreas'],true);


$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];

$InsertarIndicador=$conexion->prepare("INSERT INTO indicadores (nombre_indicador,detalle_indicador,url_indicador,quien_indicador,cuando_indicador) VALUES (:1,:2,:3,:4,:5)");
$InsertarIndicador->bindParam(':1',$datos['nombre']);
$InsertarIndicador->bindParam(':2',$datos['detalle']);
$InsertarIndicador->bindParam(':3',$datos['url']);
$InsertarIndicador->bindParam(':4',$quien);
$InsertarIndicador->bindParam(':5',$cuando);
if($InsertarIndicador->execute()){
    $idIndicador = $conexion -> lastInsertId();
    foreach($datos['arrayAreas'] as $Area){
        $InsertarHabilitacion=$conexion->prepare("INSERT INTO relacionesIndicadores (idIndicador_relacion,idArea_relacion,quien_relacion,cuando_relacion) VALUES (:1,:2,:3,:4)");
        $InsertarHabilitacion->bindParam(':1',$idIndicador);
        $InsertarHabilitacion->bindParam(':2',$Area['idArea']);
        $InsertarHabilitacion->bindParam(':3',$quien);
        $InsertarHabilitacion->bindParam(':4',$cuando);
        $InsertarHabilitacion->execute();
    }
    $detalle = "NUEVO INDICADOR CREADO. ID: " . $idIndicador;
    InsertarAuditoria($detalle,'INDICADORES',$quien);
    $res = array (
        "success"  => true,
        "id"  => $idIndicador
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $InsertarIndicador->errorInfo()
    );
}

echo json_encode($res);


?>