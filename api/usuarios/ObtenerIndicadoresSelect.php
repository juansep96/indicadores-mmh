<?php

require_once('../PDO.php');

$ObtenerIndicadoresSelect = $conexion -> prepare("SELECT * FROM indicadores WHERE estado_indicador=1");
$ObtenerIndicadoresSelect -> execute();

$result = $ObtenerIndicadoresSelect->fetchAll(\PDO::FETCH_ASSOC);
print_r (json_encode($result));

?>