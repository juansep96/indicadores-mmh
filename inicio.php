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


  <title>Panel de Bienvenida - Intranet Indicadores</title>

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
                <script>
                    $.post('./api/ObtenerSesion')
                    .then(async (data)=>{
                        userData = JSON.parse(data);
                        let html = "";
                        if(userData.nivel_usuario){
                            switch (userData.nivel_usuario) {
                            case 'ADMINISTRADOR':
                                html = '<h4>ELIJA UNA SECRETARIA</h4>';
                                html = html + '<div id="secretarias" class="row mt-2">';
                                card = "";
                                userData.secretarias.forEach((e)=>{
                                    link = "./secretaria?id=" + e.id_secretaria;
                                    icono = e.icono_secretaria;
                                    nombre = e.nombre_secretaria;
                                    card = card + '<div class="card col-6 p-1">';
                                    card = card +  '<i style="font-size:3em;" class="'+icono+'"></i>';
                                    card = card + '<div class="card-body">';
                                    card = card +  '<h5 class="card-title">'+nombre.toUpperCase()+'</h5>';
                                    card = card +  '<a href="'+link+'" class="btn btn-primary mt-2">Acceder</a>';
                                    card = card +  '</div>';
                                    card = card + '</div>';
                                })
                                
                                html = html + card + '</div>';
                                $("#content").html(html);
                            break;
                            case 'FUNCIONARIO':
                                html = '<h4>ELIJA UNA SECRETARIA</h4>';
                                html = html + '<div id="secretarias" class="row mt-2">';
                                card = "";
                                userData.secretarias.forEach((e)=>{
                                    link = "./secretaria?id=" + e.id_secretaria;
                                    icono = e.icono_secretaria;
                                    nombre = e.nombre_secretaria;
                                    card = card + '<div class="card col-6 p-1">';
                                    card = card +  '<i style="font-size:3em;" class="'+icono+'"></i>';
                                    card = card + '<div class="card-body">';
                                    card = card +  '<h5 class="card-title">'+nombre.toUpperCase()+'</h5>';
                                    card = card +  '<a href="'+link+'" class="btn btn-primary mt-2">Acceder</a>';
                                    card = card +  '</div>';
                                    card = card + '</div>';
                                })
                                
                                html = html + card + '</div>';
                                $("#content").html(html);
                            break;
                            case 'NIVEL 1':
                                html = '<h4 class="mt-2">SECRETARIA: '+userData.nombre_secretaria.toUpperCase()+'</h4>';
                                html = html + '<h4 class="mt-2">ELIJA UN AREA</h4>';
                                html = html + '<div id="areas" class="row mt-2">';
                                card = "";
                                console.log(userData.areas);
                                userData.areas.forEach((e)=>{
                                    link = "./area?id=" + e.id_area;
                                    nombre = e.nombre_area;
                                    card = card + '<div class="card col-6">';
                                    card = card + '<div class="card-body">';
                                    card = card +  '<h5 class="card-title">'+nombre.toUpperCase()+'</h5>';
                                    card = card +  '<a href="'+link+'" class="btn btn-primary mt-2">Acceder</a>';
                                    card = card +  '</div>';
                                    card = card + '</div>';
                                })
                                html = html + card + '</div>';
                                $("#content").html(html);
                            break;
                            case 'NIVEL 2':
                                html = '<h4 class="mt-2">SECRETARIA: '+userData.nombre_secretaria.toUpperCase()+'</h4>';
                                html = html + '<h4 class="mt-2">AREA: '+userData.nombre_area.toUpperCase()+'</h4>';
                                html = html + '<div id="indicadores" class="row mt-2">';
                                card = "";
                                console.log(userData);
                                userData.indicadores.forEach((e)=>{
                                    link = e.url_indicador;
                                    nombre = e.nombre_indicador;
                                    card = card + '<div class="card col-6">';
                                    card = card + '<div class="card-body">';
                                    card = card +  '<h5 class="card-title">'+nombre.toUpperCase()+'</h5>';
                                    card = card +  '<a target="_blank" href="'+link+'" class="btn btn-primary mt-2">Acceder</a>';
                                    card = card +  '</div>';
                                    card = card + '</div>';
                                })
                                html = html + card + '</div>';
                                $("#content").html(html);
                            break;
                            case 'NIVEL 3':
                                html = '<h4 class="mt-2">INDICADORES DISPONIBLES</h4>';
                                html = html + '<div id="indicadores" class="row mt-2">';
                                card = "";
                                userData.indicadores.forEach((e)=>{
                                    link = e.url_indicador;
                                    nombre = e.nombre_indicador;
                                    e.detalle_indicador ? detalle = e.detalle_indicador : detalle = '';
                                    card = card + '<div class="card col-6">';
                                    card = card + '<div class="card-body">';
                                    card = card +  '<h5 class="card-title">'+nombre+'</h5>';
                                    card = card +  '<p class="card-text">'+detalle+'</p>';
                                    card = card +  '<a target="_blank" href="'+link+'" class="btn btn-primary mt-2">Acceder</a>';
                                    card = card +  '</div>';
                                    card = card + '</div>';
                                })
                                html = html + card + '</div>';
                                $("#content").html(html);
                            break;
                        }
                        }else{
                            window.location.href = "./index";
                        }
                        
                    })
                </script>


                <div id="mensajes" class="row">
                    <?php
                        require_once('./api/PDO.php');
                        $ObtenerMensajesDisponibles = $conexion -> prepare ("SELECT * FROM mensajes WHERE estado_mensaje=1");
                        $ObtenerMensajesDisponibles -> execute();
                        if($ObtenerMensajesDisponibles -> RowCount()>0){ ?>
                                <div class="table-responsive text-center">
                                    <h4 class="text-center">Comunicados</h4>
                                    <table id="mensajes" class="table table-striped table-bordered ">
                                        <thead>
                                        <tr>
                                            <th>TITULO</th>
                                            <th>DETALLE</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $table = "";
                                                foreach($ObtenerMensajesDisponibles as $Msj){
                                                    $table = $table . '<tr>';
                                                    $table = $table .  '<td>'.$Msj['titulo_mensaje'].'</td>';
                                                    $table = $table .  '<td>'.$Msj['detalle_mensaje'].'</td>';
                                                    $table = $table . '</tr>';
                                                }

                                                echo $table;
                                            
                                            
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                    <?php } ?>
                <div>
                <div id="content" class="row">
                <div>
              
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
