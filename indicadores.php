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

  <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="./assets/plugins/notifications/css/lobibox.min.css" />


  <!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />

  <!--Theme Styles-->
  <link href="assets/css/light-theme.css" rel="stylesheet" />
  <link href="assets/css/header-colors.css" rel="stylesheet" />

  <script src="./assets/js/jquery-3.6.0.js" ></script>

  <script src="./js/ObtenerSesion.js"></script>

  <title>Indicadores - Intranet Indicadores</title>

</head>

<body onload="ObtenerIndicadores();ObtenerSecretariasSelect();">
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
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Indicadores</div>
				</div>

                <div class="row">
					<div class="col-xl-12 col-xs-12 mx-auto">
						<div class="card mt-1">
							<div class="card-body" style="margin-left:30px !important">
                                <div class="row mb-4">
                                    <div class="input-group p-2">
                                      <span style="float:right !important;" class="btn btn-dark " onclick="$('#modalNuevoIndicador').modal('show');">Nuevo Indicador</span>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="indicadores" class="table table-striped table-bordered ">
                                        <thead>
                                        <tr>
                                            <th>NOMBRE</th>
                                            <th>URL</th>
                                            <th>CREADO EL</th>
                                            <th>CREADO POR</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
  					    </div>
  				    </div>
            </div>
          </main>
       <!--end page main-->
  </div>
  <!--end wrapper-->

  <div class="modal fade" id="modalNuevoIndicador" tabindex="-1" role="dialog" aria-labelledby="modalMetodo" aria-hidden="true"><!-- Metodos de Pago -->
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Indicador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row p-2">
                    <div class="input-group p-2 ">
                        <span class="input-group-text">NOMBRE</span><input type="text" id="nombre" class="form-control">
                        <span class="input-group-text">URL</span><input type="text" id="url" class="form-control">
                    </div>

                    <div class="input-group p-2 ">
                        <span class="input-group-text">DETALLE</span><textarea id="detalle" class="form-control"></textarea>
                    </div>

                    <div class="input-group p-2 ">
                        <span class="input-group-text">SECRETARIA</span>
                        <select id="secretaria" class="form-control" onchange="ObtenerAreasSelect();">
                            <option value="0" selected disabled >Seleccione Una</option>
                        </select>

                        <span class="input-group-text">AREA</span>
                        <select id="area" class="form-control">
                        </select>
                        <button onclick="AgregarArea();" type="button" class="btn btn-dark radius-5"><i class="bi bi-plus"></i></button>

                    </div>

                    <div class="input-group p-2 text-center ">
                        <div class=" col-12">
                        <h5>Areas Habilitadas</h5>
                            <table id="areas" class="table table-striped table-bordered ">
                                <thead>
                                <tr>
                                    <th>SECRETARIA</th>
                                    <th>AREA</th>
                                    <th>ELIMINAR</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="input-group p-2">
                    <button onclick="InsertarIndicador();" type="button" class="btn btn-dark radius-5"><i class="bi bi-plus"></i>Guardar</button>
                    </div>
                </div>
            </div>
        </div>
 </div>
 </div>

 <div class="modal fade" id="modalVerIndicador" tabindex="-1" role="dialog" aria-labelledby="modalMetodo" aria-hidden="true"><!-- Metodos de Pago -->
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ver Indicador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row p-2">

                    <div class="input-group p-2 ">
                        <span class="input-group-text">NOMBRE</span><input readonly type="text" id="nombre_ver" class="form-control">
                        <span class="input-group-text">URL</span><input readonly type="text" id="url_ver" class="form-control">
                    </div>

                    <div class="input-group p-2 ">
                        <span class="input-group-text">DETALLE</span><textarea readonly id="detalle_ver" class="form-control"></textarea>
                    </div>

                    <div class="input-group p-2 text-center ">
                        <div class=" col-12">
                        <h5>Areas Habilitadas</h5>

                            <table id="areas_ver" class="table table-striped table-bordered ">
                                <thead>
                                <tr>
                                    <th>SECRETARIA</th>
                                    <th>AREA</th>
                                    <th>QUIEN</th>
                                    <th>CUANDO</th>

                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
 </div>
 </div>


 <div class="modal fade" id="modalEditarIndicador" tabindex="-1" role="dialog" aria-labelledby="modalMetodo" aria-hidden="true"><!-- Metodos de Pago -->
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Indicador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <input hidden type="text" id="idIndicador_edit" class="form-control">
                <div class="row p-2">
                    <div class="input-group p-2 ">
                        <span class="input-group-text">NOMBRE</span><input type="text" id="nombre_edit" class="form-control">
                        <span class="input-group-text">URL</span><input type="text" id="url_edit" class="form-control">
                    </div>

                    <div class="input-group p-2 ">
                        <span class="input-group-text">DETALLE</span><textarea id="detalle_edit" class="form-control"></textarea>
                    </div>

                    <div class="input-group p-2 ">
                        <span class="input-group-text">SECRETARIA</span>
                        <select id="secretaria_edit" class="form-control" onchange="ObtenerAreaSelectEdit();">
                            <option value="0" selected disabled >Seleccione Una</option>
                        </select>

                        <span class="input-group-text">AREA</span>
                        <select id="area_edit" class="form-control">
                        </select>
                        <button onclick="AgregarAreaEdit();" type="button" class="btn btn-dark radius-5"><i class="bi bi-plus"></i></button>

                    </div>
                   
                    <div class="input-group p-2 text-center ">
                        <div class=" col-12">
                        <h5>Areas Habilitadas</h5>

                            <table id="areas_edit" class="table table-striped table-bordered ">
                                <thead>
                                <tr>
                                    <th>SECRETARIA</th>
                                    <th>AREA</th>
                                    <th>QUIEN</th>
                                    <th>CUANDO</th>
                                    <th>ELIMINAR</th>

                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                   

                    <div class="input-group p-2">
                    <button onclick="ActualizarIndicador();" type="button" class="btn btn-dark radius-5"><i class="bi bi-refresh"></i>Actualizar</button>
                    </div>
                </div>
            </div>
        </div>
 </div>
 </div>

  <!-- Bootstrap bundle JS -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <!--plugins-->
  <script src="assets/js/pace.min.js"></script>
  <script src="assets/plugins/chartjs/js/Chart.min.js"></script>
  <script src="assets/plugins/chartjs/js/Chart.extension.js"></script>
  <script src="assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
  <script src="//momentjs.com/downloads/moment.min.js"></script> <!--Moments Library-->
  <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
  <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
  <script src="./assets/plugins/notifications/js/notifications.js"></script>
  <script src="./assets/plugins/notifications/js/lobibox.js"></script>


  <!--app-->
  <script src="assets/js/app.js"></script>
  <script src="./js/indicadores.js"></script>

  <script>
    new PerfectScrollbar(".best-product")
 </script>


</body>

</html>
