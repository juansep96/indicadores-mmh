<?php

require_once('../PDO.php');

$idRelacion = $_POST['idRelacion']; 

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];
$estado = 0;

$ObtenerRelacion = $conexion -> prepare("SELECT * FROM relacionesIndicadores WHERE id_relacion=:1");
$ObtenerRelacion -> bindParam(':1',$idRelacion);
$ObtenerRelacion -> execute();
foreach($ObtenerRelacion as $Rl){
    $idIndicador = $Rl['idIndicador_relacion'];
}

$EliminarRelacion=$conexion->prepare("UPDATE relacionesIndicadores SET estado_relacion=:1 WHERE id_relacion = :2");
$EliminarRelacion->bindParam(':1',$estado);
$EliminarRelacion->bindParam(':2',$idRelacion);
if($EliminarRelacion->execute()){
    $ObtenerHabilitaciones = $conexion -> prepare("SELECT * FROM relacionesIndicadores left join areas ON idArea_relacion = id_area LEFT JOIN secretarias ON idSecretaria_area = id_secretaria left join usuarios ON quien_relacion = id_usuario WHERE idIndicador_relacion=:1 AND estado_relacion=1");
    $ObtenerHabilitaciones -> bindParam(':1',$idIndicador);
    $ObtenerHabilitaciones -> execute();

    $result = $ObtenerHabilitaciones->fetchAll(\PDO::FETCH_ASSOC);

    $res = array (
        "data"  => json_encode($result),
        "success"  => true,
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $EliminarArea->errorInfo()
    );
}

echo json_encode($res);


?>