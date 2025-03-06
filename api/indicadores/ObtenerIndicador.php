<?php

require_once('../PDO.php');

$idIndicador = $_POST['idIndicador'];

$ObtenerIndicador = $conexion -> prepare("SELECT * FROM indicadores WHERE id_indicador=:1");
$ObtenerIndicador -> bindParam(':1',$idIndicador);
$ObtenerIndicador -> execute();

$result = $ObtenerIndicador->fetchAll(\PDO::FETCH_ASSOC);


$ObtenerHabilitaciones = $conexion -> prepare("SELECT * FROM relacionesIndicadores left join areas ON idArea_relacion = id_area LEFT JOIN secretarias ON idSecretaria_area = id_secretaria left join usuarios ON quien_relacion = id_usuario WHERE idIndicador_relacion=:1 AND estado_relacion=1");
$ObtenerHabilitaciones -> bindParam(':1',$idIndicador);
$ObtenerHabilitaciones -> execute();

$result2 = $ObtenerHabilitaciones->fetchAll(\PDO::FETCH_ASSOC);

$res = array (
    "data"  => json_encode($result),
    "habilitaciones" => json_encode($result2),
);

echo json_encode($res);


?>