<?php

require_once('../PDO.php');

$idSecretaria = $_POST['idSecretaria']; 

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];
$estado = 0;

$EliminarSecretaria=$conexion->prepare("UPDATE secretarias SET estado_secretaria=:1 WHERE id_secretaria = :2");
$EliminarSecretaria->bindParam(':1',$estado);
$EliminarSecretaria->bindParam(':2',$idSecretaria);
if($EliminarSecretaria->execute()){
    $detalle = "SECRETARIA ELIMINADA. ID: " . $idSecretaria;
    InsertarAuditoria($detalle,'SECRETARIAS',$quien);
    $res = array (
        "success"  => true,
        "id"  => $idSecretaria
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $EliminarSecretaria->errorInfo()
    );
}

echo json_encode($res);


?>