<?php
require_once('./api/PDO.php');
$idArea = (int)$_GET['id'];
if(!isset($_GET['id']) || empty($_GET['id']) || !is_int($idArea)){
    echo "<script>window.location.href='./inicio.php'</script>";
}

$puede = false;

$ObtenerDatosArea = $conexion -> prepare ("SELECT * from areas WHERE id_area = :1 AND estado_area = 1");
$ObtenerDatosArea -> bindParam(':1',$idArea);
$ObtenerDatosArea -> execute();
if($ObtenerDatosArea -> RowCount () > 0){
    foreach($ObtenerDatosArea as $Ar){
        $nombreArea = $Ar['nombre_area'];
        $idSecretaria = $Ar['idSecretaria_area'];
    }
}

switch ($_SESSION['nivel_usuario']) {
    case 'ADMINISTRADOR':
        $puede = true;
            $ObtenerIndicadores = $conexion -> prepare ("SELECT * from relacionesIndicadores left join indicadores ON idIndicador_relacion = id_indicador WHERE idArea_relacion = :1 AND estado_indicador=1 AND estado_relacion=1  ORDER BY nombre_indicador ASC");
            $ObtenerIndicadores -> bindParam(':1',$idArea);
            $ObtenerIndicadores -> execute();
    break;
    case 'FUNCIONARIO':
       $puede = true;
       $ObtenerIndicadores = $conexion -> prepare ("SELECT * from relacionesIndicadores left join indicadores ON idIndicador_relacion = id_indicador WHERE idArea_relacion = :1 AND estado_indicador=1 AND estado_relacion=1  ORDER BY nombre_indicador ASC");
            $ObtenerIndicadores -> bindParam(':1',$idArea);
            $ObtenerIndicadores -> execute();
    break;
    case 'NIVEL 1':
        //Verificamos que el ID del area que traemos por GET pertenezca a la secretaria 
        //a la cual pertenece el usuario
        if($_SESSION['idSecretaria_usuario'] == $idSecretaria){
            //Consultamos los indicadores habilitados del area
            $ObtenerIndicadores = $conexion -> prepare ("SELECT * from relacionesIndicadores left join indicadores ON idIndicador_relacion = id_indicador WHERE idArea_relacion = :1 AND estado_indicador=1 AND estado_relacion=1 ORDER BY nombre_indicador ASC");
            $ObtenerIndicadores -> bindParam(':1',$idArea);
            $ObtenerIndicadores -> execute();
            $puede = true;
        }
    break;
    case 'NIVEL 2':
        //Verificamos que el id que esta consultando, sea el mismo que tiene habilitado en su perfil.
        if($_SESSION['idArea_usuario'] == $idArea){
            $puede = true;
            //Consultamos los indicadores habilitados del area
            $ObtenerIndicadores = $conexion -> prepare ("SELECT * from relacionesIndicadores left join indicadores ON idIndicador_relacion = id_indicador WHERE idArea_relacion = :1 AND estado_indicador=1 AND estado_relacion=1 ORDER BY nombre_indicador ASC");
            $ObtenerIndicadores -> bindParam(':1',$idArea);
            $ObtenerIndicadores -> execute();
        }
    break;
    case 'NIVEL 3':

    break;
    default:
        //Los demas niveles no pueden acceder a la web
    break;
}
if(!$puede){
    echo "<script>window.location.href='./inicio.php'</script>";
}else{
    
    $ObtenerSecretaria = $conexion -> prepare ("SELECT * FROM secretarias WHERE id_secretaria = :1 AND estado_secretaria=1");
    $ObtenerSecretaria -> bindParam(':1',$idSecretaria);
    $ObtenerSecretaria -> execute();

    if($ObtenerSecretaria -> RowCount() == 0){
        echo "<script>window.location.href='./inicio.php'</script>";
    }else{
        foreach($ObtenerSecretaria as $Scr){
            $nombreSecretaria = strtoupper($Scr['nombre_secretaria']);
        }
    }
}
?>

<!doctype html>
<html lang="es" class="light-theme">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
  <!--plugins-->
  <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
  <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
  <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
  <!-- Bootstrap CSS -->
  <link href="assets/css/bootstrap.css" rel="stylesheet" />
  <link href="assets/css/bootstrap-extended.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <link href="assets/css/icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">


  <!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />

  <!--Theme Styles-->
  <link href="assets/css/light-theme.css" rel="stylesheet" />
  <link href="assets/css/header-colors.css" rel="stylesheet" />

  <script src="./assets/js/jquery-3.6.0.js" ></script>

  <script src="./js/ObtenerSesion.js"></script>


  <title>Ver Secretaria - Intranet Indicadores</title>

</head>

<body>
  <!--Inicio del Contenedor-->
  <div class="wrapper">
    <div id="menu"></div>
      <script>
          $.get("menu.php", function(data){
              $("#menu").html(data);
          });

      </script>

       <!--Inicio de Pagina para Admins con Graficos-->
          <main class="page-content">
            <div class="row m-3 text-center">
            <?php 
                if($ObtenerIndicadores -> RowCount () > 0){
                    $html = '<h4 class="mt-5">SECRETARIA: '.$nombreSecretaria.'</h4>';
                    $html = '<h4 class="mt-5">AREA: '.$nombreArea.'</h4>';
                    $html = $html . '<div id="indicadores" class="row mt-5">';
                    $card = "";
                      foreach($ObtenerIndicadores as $Ar){
                        $link = $Ar['url_indicador'];
                        $nombre = $Ar['nombre_indicador'];
                        $detalle = $Ar['detalle_indicador'];
                        $card = $card . '<div class="card col-12 p-1">';
                        $card = $card . '<div class="card-body">';
                        $card = $card .  '<h5 class="card-title">'.$nombre.'</h5>';
                        $card = $card .  '<p class="card-text">'.$detalle.'</p>';
                        $card = $card .  '<a target="_blank" href="'.$link.'" class="btn btn-primary mt-2">Acceder</a>';
                        $card = $card .  '</div>';
                        $card = $card . '</div>';
                      }
                      $html = $html . $card . '</div>';
                      echo $html;
                }else{
                    echo '<h4 class="mt-5">SECRETARIA: '.$nombreSecretaria.'</h4>';
                    echo '<h4 class="mt-5">AREA: '.$nombreArea.'</h4>';
                    echo '<h4 class="mt-5">NO SE ENCONTRARON INDICADORES DE ESTE AREA.</h4>';
                }
              
            ?>
            </div>
          </main>
       <!--end page main-->
  </div>
  <!--end wrapper-->


  <!-- Bootstrap bundle JS -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <!--plugins-->
  <script src="assets/js/pace.min.js"></script>

</body>

</html>
