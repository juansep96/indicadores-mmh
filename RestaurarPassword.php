<?php
require_once('./api/PDO.php');
$code = $_GET['code'];
$puede = false;

if(!isset($_GET['code']) || empty($_GET['code'])){
    echo "<script>window.location.href='./index'</script>";
}else{
    //A chequear si el codigo esta bien y guardamos en una variable de sesioin el id
    $ObtenerUsuario = $conexion-> prepare("SELECT * from usuarios WHERE claveRecupero_usuario=:1 AND estado_usuario=1");
    $ObtenerUsuario -> bindParam(":1",$code);
    $ObtenerUsuario -> execute();
    if($ObtenerUsuario -> RowCount()>0){
        $puede = true;
        foreach($ObtenerUsuario  as $Usr){
            $Usr2 = $Usr['id_usuario'];
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
  <!-- Bootstrap CSS -->
  <link href="assets/css/bootstrap.css" rel="stylesheet" />
  <link href="assets/css/bootstrap-extended.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <link href="assets/css/icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <!--plugins-->
  <link rel="stylesheet" href="./assets/plugins/notifications/css/lobibox.min.css" />

  <!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />

  <title>Restaurar Contraseña - Intranet Indicadores - Municipalidad de Monte Hermoso</title>
</head>

<body>

  <!--start wrapper-->
  <div class="wrapper">

       <!--start content-->
       <main class="authentication-content">
        <div class="container-fluid">
          <div class="authentication-card">
            <div class="card shadow rounded-0 overflow-hidden">
              <div class="row g-0">
                <div class="col-lg-6 bg-login d-flex align-items-center justify-content-center" style="background:white !important">
                  <img src="./assets/images/logo.png" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6">
                  <div class="card-body p-4 p-sm-5">
                    <?php

                    if($puede) {

                    ?> 
                    <input type="text" hidden id="id_restaurar" value="<?php echo $Usr2;?>">
                        <h5 class="card-title text-center">Resturar Contraseña</h5>
                        <p class="card-text mb-5 text-center">Ingrese su nueva contraseña de acceso.</p>
                        <div class="form-body">
                            <div class="row g-3">
                            <div class="col-12">
                                <label for="inputEmailAddress" class="form-label">Contraseña</label>
                                <div class="ms-auto position-relative">
                                <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-lock-fill"></i></div>
                                <input type="password" class="form-control radius-30 ps-5" id="password2" placeholder="Ingrese Contraseña">
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="inputChoosePassword" class="form-label">Repita contraseña</label>
                                <div class="ms-auto position-relative">
                                <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-lock-fill"></i></div>
                                <input type="password" class="form-control radius-30 ps-5" id="password" placeholder="Repita Contraseña">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                <button onclick="restore();"  class="btn btn-red radius-30">Restaurar</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    <?php } else{ ?>
                        <h5 class="card-title text-center">ENLACE INVALIDO</h5>
                    <?php } ?>
                 </div>
                </div>
              </div>
            </div>
          </div>
        </div>
       </main>

       <!--end page main-->

  </div>
  <!--end wrapper-->


  <!-- Bootstrap bundle JS -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <!--plugins-->
  <script src="./assets/js/jquery-3.6.0.js"></script>
  <!--notification js -->
  <script src="./assets/plugins/notifications/js/notifications.js"></script>
  <script src="./assets/js/pace.min.js"></script>
  <!--app-->
  <script src="./js/restore.js?v=0.05"></script>



</body>

</html>
