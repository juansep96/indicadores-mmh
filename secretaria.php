<?php
require_once('./api/PDO.php');
$idSecretaria = (int)$_GET['id'];
if(!isset($_GET['id']) || empty($_GET['id']) || !is_int($idSecretaria)){
    echo "<script>window.location.href='./inicio.php'</script>";
}

$puede = false;

switch ($_SESSION['nivel_usuario']) {
    case 'ADMINISTRADOR':
        $puede = true;
    break;
    case 'FUNCIONARIO':
       $puede = true;
    break;
    case 'NIVEL 1':
        //Verificamos que el Id que esta consultando, sea el mismo que tiene habilitado en su perfil.
        if($_SESSION['idSecretaria_usuario'] == $idSecretaria){
            $puede = true;
        }
    break;
    default:
        //Los demas niveles no pueden acceder a la web
    break;
}
if(!$puede){
    echo "<script>window.location.href='./inicio.php'</script>";
}else{
    //Vamos a obtener las areas de la secretaria donde estamos 
    $ObtenerAreas = $conexion -> prepare ("SELECT * FROM areas WHERE idSecretaria_area = :1 AND estado_area=1 ORDER BY nombre_area ASC");
    $ObtenerAreas -> bindParam(':1',$idSecretaria);
    $ObtenerAreas -> execute();

    $ObtenerSecretaria = $conexion -> prepare ("SELECT * FROM secretarias WHERE id_secretaria = :1 AND estado_secretaria=1 ");
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
                if($ObtenerAreas -> RowCount () > 0){
                    echo '<h4 class="mt-5">SECRETARIA: '.$nombreSecretaria.'</h4>';
                    echo '<h4 class="mt-5">ELIJA UN AREA</h4>';
                    echo '<div id="areas" class="row mt-5">';
                      foreach($ObtenerAreas as $Ar){
                        $card = "";
                        $link = "./area?id=" . $Ar['id_area'];
                        $nombre = $Ar['nombre_area'];
                        $card = $card . '<div class="card col-6 p-1">';
                        $card = $card . '<div class="card-body">';
                        $card = $card .  '<h5 class="card-title">'.$nombre.'</h5>';
                        $card = $card .  '<a href="'.$link.'" class="btn btn-primary mt-2">Acceder</a>';
                        $card = $card .  '</div>';
                        $card = $card . '</div>';
                        echo $card;
                      }
                      echo '</div>';
                }else{
                    echo '<h4 class="mt-5">SECRETARIA: '.$nombreSecretaria.'</h4>';
                    echo '<h4 class="mt-5">NO SE ENCONTRARON AREAS HABILITADAS EN ESTA SECRETARIA.</h4>';
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
