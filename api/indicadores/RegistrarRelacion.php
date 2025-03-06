<?php

require_once('../PDO.php');

$datos=$_POST['data'];
$datos=json_decode($datos,true);

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];

$InsertarHabilitacion=$conexion->prepare("INSERT INTO relacionesIndicadores (idIndicador_relacion,idArea_relacion,quien_relacion,cuando_relacion) VALUES (:1,:2,:3,:4)");
$InsertarHabilitacion->bindParam(':1',$datos['idIndicador']);
$InsertarHabilitacion->bindParam(':2',$datos['idArea']);
$InsertarHabilitacion->bindParam(':3',$quien);
$InsertarHabilitacion->bindParam(':4',$cuando);
if($InsertarHabilitacion->execute()){
    $ObtenerHabilitaciones = $conexion -> prepare("SELECT * FROM relacionesIndicadores left join areas ON idArea_relacion = id_area LEFT JOIN secretarias ON idSecretaria_area = id_secretaria left join usuarios ON quien_relacion = id_usuario WHERE idIndicador_relacion=:1 AND estado_relacion=1");
    $ObtenerHabilitaciones -> bindParam(':1',$datos['idIndicador']);
    $ObtenerHabilitaciones -> execute();

    $result = $ObtenerHabilitaciones->fetchAll(\PDO::FETCH_ASSOC);

    $res = array (
        "data"  => json_encode($result),
        "success"  => true,
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $InsertarHabilitacion->errorInfo()
    );
}

echo json_encode($res);


?>