<?php

require_once('../PDO.php');

$ObtenerSecretariasSelect = $conexion -> prepare("SELECT * FROM secretarias WHERE estado_secretaria=1");
$ObtenerSecretariasSelect -> execute();

$result = $ObtenerSecretariasSelect->fetchAll(\PDO::FETCH_ASSOC);
print_r (json_encode($result));

?>