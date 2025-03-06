<?php

require_once('../PDO.php');

$idSecretaria = $_POST['idSecretaria'];

$ObtenerAreasSelect = $conexion -> prepare("SELECT * FROM areas WHERE estado_area=1 AND idSecretaria_area=:1");
$ObtenerAreasSelect -> bindParam(':1',$idSecretaria);
$ObtenerAreasSelect -> execute();

$result = $ObtenerAreasSelect->fetchAll(\PDO::FETCH_ASSOC);
print_r (json_encode($result));

?>