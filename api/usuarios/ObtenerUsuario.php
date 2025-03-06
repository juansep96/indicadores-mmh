<?php

require_once('../PDO.php');

$idUsuario = $_POST['idUsuario'];

$ObtenerUsuario = $conexion -> prepare("SELECT * FROM usuarios LEFT JOIN secretarias ON id_secretaria = idSecretaria_usuario left join areas ON id_area = idArea_usuario WHERE id_usuario=:1");
$ObtenerUsuario -> bindParam(':1',$idUsuario);
$ObtenerUsuario -> execute();

$result = $ObtenerUsuario->fetchAll(\PDO::FETCH_ASSOC);
print_r (json_encode($result));

?>