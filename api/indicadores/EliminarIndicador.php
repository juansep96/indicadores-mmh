<?php

require_once('../PDO.php');

$idIndicador = $_POST['idIndicador']; 

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];
$estado = 0;

$Eliminarindicador=$conexion->prepare("UPDATE indicadores SET estado_indicador=:1 WHERE id_indicador = :2");
$Eliminarindicador->bindParam(':1',$estado);
$Eliminarindicador->bindParam(':2',$idIndicador);
if($Eliminarindicador->execute()){
    $detalle = "INDICADOR ELIMINADO. ID: " . $idIndicador;
    InsertarAuditoria($detalle,'INDICADORES',$quien);
    $res = array (
        "success"  => true,
        "id"  => $idIndicador
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $Eliminarindicador->errorInfo()
    );
}

echo json_encode($res);


?>