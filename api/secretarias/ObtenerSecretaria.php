<?php

require_once('../PDO.php');

$idSecretaria = $_POST['idSecretaria'];

$ObtenerSecretaria = $conexion -> prepare("SELECT * FROM secretarias WHERe id_secretaria=:1");
$ObtenerSecretaria -> bindParam(':1',$idSecretaria);
$ObtenerSecretaria -> execute();

$result = $ObtenerSecretaria->fetchAll(\PDO::FETCH_ASSOC);
print_r (json_encode($result));

?>