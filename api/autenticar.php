<?php

require_once('./PDO.php');

if(isset($_POST['token']) && $_POST['token']!=''){

    //Vamos a validar que el token sea valido. 
    
   $token = $_POST['token'];
    $url = 'https://autogestion.montehermoso.gov.ar/backoffice/public/api/developers/token/validate';
    $post = array('token'=>$token);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_VERBOSE,true);
    $respuesta = curl_exec ($ch);
    $respuesta = json_decode($respuesta, true);
    if(isset($respuesta['success'])){
        //Token valido, ahora tenemos que verificar si tiene acceso al sistema
        $dniPersona = $respuesta['user']['dni'];
        $IniciarSesion = $conexion->prepare("SELECT * from usuarios left join secretarias ON idSecretaria_usuario = id_secretaria left join areas ON idArea_usuario = id_area WHERE dni_usuario=:1 AND estado_usuario=1");
        $IniciarSesion -> bindParam(':1',$dniPersona);
        $IniciarSesion -> execute();
        if($IniciarSesion->RowCount()>0){
            foreach ($IniciarSesion as $Account){
                $_SESSION = $Account;
                $secretarias = array ();
                $areas = array ();
                $indicadores = array ();
                switch ($Account['nivel_usuario']) {
                    case 'ADMINISTRADOR':
                        $ObtenerSecretarias = $conexion -> prepare ("SELECT * FROM secretarias WHERE estado_secretaria = 1  ORDER BY nombre_secretaria ASC");
                        $ObtenerSecretarias -> execute();
                        if($ObtenerSecretarias -> RowCount() > 0){
                            foreach($ObtenerSecretarias as $Secretaria){
                                array_push($secretarias,$Secretaria);
                            }
                        }
                    break;
                    case 'FUNCIONARIO':
                        $ObtenerSecretarias = $conexion -> prepare ("SELECT * FROM secretarias WHERE estado_secretaria = 1  ORDER BY nombre_secretaria ASC");
                        $ObtenerSecretarias -> execute();
                        if($ObtenerSecretarias -> RowCount() > 0){
                            foreach($ObtenerSecretarias as $Secretaria){
                                array_push($secretarias,$Secretaria);
                            }
                        }
                    break;
                    case 'NIVEL 1':
                        $ObtenerAreas = $conexion -> prepare ("SELECT * FROM areas WHERE estado_area = 1 AND idSecretaria_area = :1 ORDER BY nombre_area ASC");
                        $ObtenerAreas -> bindParam(':1',$_SESSION['idSecretaria_usuario']);
                        $ObtenerAreas -> execute();
                        if($ObtenerAreas -> RowCount() > 0){
                            foreach($ObtenerAreas as $Area){
                                array_push($areas,$Area);
                            }
                        }
                    break;
                    case 'NIVEL 2':
                        $ObtenerIndicadores = $conexion -> prepare ("SELECT * FROM indicadores LEFT JOIN relacionesIndicadores ON idIndicador_relacion = id_indicador WHERE estado_indicador = 1 AND idArea_relacion = :1 AND estado_relacion=1 ORDER BY nombre_indicador ASC");
                        $ObtenerIndicadores -> bindParam(':1',$_SESSION['idArea_usuario']);
                        $ObtenerIndicadores -> execute();
                        if($ObtenerIndicadores -> RowCount() > 0){
                            foreach($ObtenerIndicadores as $Indicador){
                                array_push($indicadores,$Indicador);
                            }
                        }
                    break;
                    case 'NIVEL 3':
                        //Vamos a tener que obtener los indicadores cuyos IDs estan dentro de Ids
                        $listaIndicadores = json_decode($Account['idIndicadores_usuario']);
                        $listaIndicadores = array_map('intval', $listaIndicadores);
                        foreach($listaIndicadores as $listaIndicador){
                            $ObtenerIndicador = $conexion -> prepare ("SELECT * FROM indicadores WHERE estado_indicador = 1 AND id_indicador = :1 ");
                            $ObtenerIndicador -> bindparam(':1',$listaIndicador);
                            $ObtenerIndicador -> execute();
                            if($ObtenerIndicador -> RowCount() > 0){
                                foreach($ObtenerIndicador as $Indicador){
                                    array_push($indicadores,$Indicador);
                                }
                            }
                        }                
                    break;
                }
                $res = array (
                    "login"  => true,
                    "data"  => $Account,
                    "secretarias" => $secretarias,
                    "areas" => $areas,
                    "indicadores" => $indicadores
                );
                $_SESSION['secretarias'] = $secretarias;
                $_SESSION['areas'] = $areas;
                $_SESSION['indicadores'] = $indicadores;
                //Tenemos que actualizar el ultimo logueo del usuario e insertar el registro.
                RegistrarLogin($Account['id_usuario']);
                InsertarAuditoria('INICIO DE SESION','USUARIOS',$Account['id_usuario']);
            }
            $code = 200;
            $error = "";
        }else{
            //Persona no tiene acceso
            $code = 202;
            $error = "NO TIENE ACCESO AL SISTEMA.";
        }
    }else{
        //Token invalido.
        $code = 201;
        $error = "TOKEN INVALIDO";
        
    }

}else{
    $code = 201;
    $error = "TOKEN NO PROPORCIONADO";
}

$data = [
    "code" => $code,
    "error" => $error
];

print_r(json_encode($data));

?>