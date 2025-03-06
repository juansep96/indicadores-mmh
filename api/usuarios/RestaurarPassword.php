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

if(!$emailUser){
    $res = array (
        "success"  => false,
        "error"  => "Correo Electronico invalido"
    );
}else{
    $RestaurarUsuario=$conexion->prepare("UPDATE usuarios SET claveRecupero_usuario=:1 WHERE id_usuario = :2");
    $RestaurarUsuario->bindParam(':1',$nuevaPassword);
    $RestaurarUsuario->bindParam(':2',$idUsuario);
    if($RestaurarUsuario->execute()){
        $link = "http://sd-3726013-h00005.ferozo.net/intranet/RestaurarPassword?code=".$nuevaPassword;
        $subject = 'RESETEAR CONTRASEÑA - MUNICIPALIDAD DE MONTE HERMOSO';
        $msg = "<h2>Estimado Usuario, hemos recibido una solicitud para blanquear su contraseña.</h2><br>";
        $msg = $msg . "<p>Ingrese al siguiente link para restaurar su contraseña. </p></br>";
        $msg = $msg . "<p>Si no funciona al hacer click, puede copiar y pegar el link en la barra de direcciones.</p></br>";
        $msg = $msg . "<a href=".$link.">".$link."</p></br>";
        $to      = $emailUser;
        $headers = 'From: Municipalidad de Monte Hermoso <no-reply@montehermoso.gov.ar>' . "\r\n" .
            'Reply-To: Municipalidad de Monte Hermoso <no-reply@montehermoso.gov.ar>' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
            $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
        $sent = mail($to, $subject, $msg, $headers);

        if($sent){
            $detalle = "RESTURAR CONTRENA USUARIO. ID: " . $idUsuario;
            InsertarAuditoria($detalle,'USUARIOS',$quien);
            $res = array (
                "success"  => true,
                "id"  => $idUsuario
            );
        }else{

        }
    
        
    }else{
        $res = array (
            "success"  => false,
            "error"  => $RestaurarUsuario->errorInfo()
        );
    }
}


echo json_encode($res);




?>