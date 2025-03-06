<?php

require_once('../PDO.php');

$idArea = $_POST['idArea'];

$ObtenerArea = $conexion -> prepare("SELECT * FROM areas WHERe id_area=:1");
$ObtenerArea -> bindParam(':1',$idArea);
$ObtenerArea -> execute();

$result = $ObtenerArea->fetchAll(\PDO::FETCH_ASSOC);
print_r (json_encode($result));

?>