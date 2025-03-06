<?php

require_once('../PDO.php');

$idUsuario = $_POST['idUsuario']; 

$cuando = date("Y-m-d H:i:s");
$quien = $_SESSION['id_usuario'];
$nuevaPassword = $rand = substr(md5(microtime()),rand(0,26),10);

$ObtenerUsuario = $conexion -> prepare ("SELECT * FROM usuarios WHERE id_usuario=:1");
$ObtenerUsuario -> bindParam(":1",$idUsuario);
$ObtenerUsuario -> execute();

foreach($ObtenerUsuario as $Usr){
    $emailUser = $Usr['correo_usuario'];
}

var_dump($emailUser);



/*

$RestaurarUsuario=$conexion->prepare("UPDATE usuarios SET claveRecupero_usuario=:1 WHERE id_usuario = :2");
$RestaurarUsuario->bindParam(':1',$nuevaPassword);
$RestaurarUsuario->bindParam(':2',$idUsuario);
if($RestaurarUsuario->execute()){

    $subject = 'DESCUENTO MAYOR AL 40% REALIZADO.';
    $msg = "<h2>Estimados, adjunto datos de la venta realizada donde se detectó que al menos un producto fue vendido con un descuento mayor o igual al 50%.</h2><br>";
    $msg = $msg . "<p>Venta: ".$idVenta."</p></br>";
    $msg = $msg . "<p>Importe: $ ".$datos['total']."</p></br>";
    $msg = $msg . "<p>Fecha: ".date('d/m/Y',strtotime($cuando))."</p></br>";
    $msg = $msg . "<p>Sucursal: ".$sucursalNombre."</p></br>";
    $to      = 'dionela@pontebellabahia.com';
    $headers = 'From: Sistemas Ponte Bella Bahia SRL <info@sistemaspb.ar>' . "\r\n" .
        'Reply-To: Sistemas Ponte Bella Bahia SRL <info@sistemaspb.ar>' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
        $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    mail($to, $subject, $msg, $headers);

    $detalle = "RESTURAR CONTRENA USUARIO. ID: " . $idUsuario;
    InsertarAuditoria($detalle,'USUARIOS',$quien);
    //Tenemos que enviar el mail

    $res = array (
        "success"  => true,
        "id"  => $idUsuario
    );
}else{
    $res = array (
        "success"  => false,
        "error"  => $RestaurarUsuario->errorInfo()
    );
}

echo json_encode($res);

*/


?>