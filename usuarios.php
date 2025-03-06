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

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


  <!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />

  <!--Theme Styles-->
  <link href="assets/css/light-theme.css" rel="stylesheet" />
  <link href="assets/css/header-colors.css" rel="stylesheet" />

  <script src="./assets/js/jquery-3.6.0.js" ></script>

  <script src="./js/ObtenerSesion.js"></script>



  <title>Usuarios - Intranet Indicadores</title>

</head>

<body onload="ObtenerUsuarios();ObtenerSecretariasSelect();ObtenerIndicadoresSelect();">
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
					<div class="breadcrumb-title pe-3">Usuarios</div>
				</div>

                <div class="row">
					<div class="col-xl-12 col-xs-12 mx-auto">
						<div class="card mt-1">
							<div class="card-body" style="margin-left:30px !important">
                                <div class="row mb-4">
                                    <div class="input-group p-2">
                                      <span style="float:right !important;" class="btn btn-dark " onclick="$('#modalNuevoUsuario').modal('show');">Nuevo Usuario</span>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="usuarios" class="table table-striped table-bordered ">
                                        <thead>
                                        <tr>
                                            <th>DNI</th>
                                            <th>APELLIDO</th>
                                            <th>NOMBRE</th>
                                            <th>NIVEL</th>
                                            <th>SECRETARIA</th>
                                            <th>AREA</th>
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

  <div class="modal fade" id="modalNuevoUsuario" tabindex="-1" role="dialog" aria-labelledby="modalMetodo" aria-hidden="true"><!-- Metodos de Pago -->
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row p-2">
                    <div class="input-group p-2 ">
                        <span class="input-group-text">DNI *</span><input type="text" id="dni" class="form-control">
                        <span class="input-group-text">APELLIDO *</span><input type="text" id="apellido" class="form-control">
                    </div>

                    <div class="input-group p-2 ">
                        <span class="input-group-text">NOMBRE *</span><input type="text" id="nombre" class="form-control">
                        <span class="input-group-text">CORREO ELEC.</span><input type="text" id="correo" class="form-control">
                    </div>

                    <div class="input-group p-2 ">
                        <span class="input-group-text">TELEFONO</span><input type="number" step="1" id="telefono" class="form-control">
                        <span class="input-group-text">NIVEL DE USUARIO  *</span>
                        <select id="nivel" class="form-control" onchange="ActualizarCampos();">
                            <option selected disabled >Seleccione Uno</option>
                            <option value="ADMINISTRADOR" >ADMINISTRADOR</option>
                            <option value="FUNCIONARIO" >FUNCIONARIO</option>
                            <option value="NIVEL 1" >NIVEL 1</option>
                            <option value="NIVEL 2" >NIVEL 2</option>
                            <option value="NIVEL 3" >NIVEL 3</option>
                        </select>
                    </div>

                    <div class="input-group p-2 ">
                        <span class="input-group-text">CONTRASENA  *</span><input type="password" id="password" class="form-control">
                        <span class="input-group-text">REPITA CONTRASENA  *</span><input type="password" id="password2" class="form-control">
                    </div>

                    <div class="input-group p-2 ">
                        <span hidden class="input-group-text nivel_1">SECRETARIA</span>
                        <select hidden id="secretaria" onchange="ObtenerAreasSelect()" class="form-control nivel_1">
                            <option selected disabled >Seleccione Uno</option>
                        </select>

                        <span hidden class="input-group-text nivel_2">AREA</span>
                        <select hidden id="area" class="form-control nivel_2">
                        </select>

                        <div hidden class="nivel_3 col-12">
                            <span for="indicadores"  class="input-group-text">INDICADORES</span>
                            <select placeholder="Elija al menos uno" class="js-example-basic-multiple" id="indicadores" multiple="multiple">
                            </select>
                        </div>
                        
                    </div>

                    <div class="input-group p-2">
                    <button onclick="InsertarUsuario();" type="button" class="btn btn-dark radius-5"><i class="bi bi-plus"></i>Guardar</button>
                    </div>
                </div>
            </div>
        </div>
 </div>
 </div>


 <div class="modal fade" id="modalVerUsuario" tabindex="-1" role="dialog" aria-labelledby="modalMetodo" aria-hidden="true"><!-- Metodos de Pago -->
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ver Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row p-2">
                    <div class="input-group p-2 ">
                        <span class="input-group-text">DNI</span><input readonly type="text" id="dni_view" class="form-control">
                        <span class="input-group-text">APELLIDO</span><input readonly type="text" id="apellido_view" class="form-control">
                    </div>

                    <div class="input-group p-2 ">
                        <span class="input-group-text">NOMBRE</span><input readonly type="text" id="nombre_view" class="form-control">
                        <span class="input-group-text">CORREO ELEC.</span><input readonly type="text" id="correo_view" class="form-control">
                    </div>

                    <div class="input-group p-2 ">
                        <span class="input-group-text">TELEFONO</span><input readonly type="number" step="1" id="telefono_view" class="form-control">
                        <span class="input-group-text">NIVEL DE USUARIO</span><input readonly type="text" step="1" id="nivel_view" class="form-control">
                    </div>

                    <div class="input-group p-2 ">
                        <span hidden class="input-group-text nivel_1">SECRETARIA</span>
                        <input readonly type="text" step="1" id="secretaria_view" class="form-control nivel_1">

                        <span hidden class="input-group-text nivel_2">AREA</span>
                        <input readonly type="text" step="1" id="area_view" class="form-control nivel_2">

                        <span hidden class="input-group-text nivel_3">INDICADORES</span>
                        <input readonly type="text" step="1" id="indicadores_view" class="form-control nivel_3">
                    </div>

                </div>
            </div>
        </div>
 </div>
 </div>

 <div class="modal fade" id="modalEditarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalMetodo" aria-hidden="true"><!-- Metodos de Pago -->
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <input hidden type="text" id="idUsuario_edit" class="form-control">
                <div class="row p-2">
                    <div class="input-group p-2 ">
                        <span class="input-group-text">DNI</span><input type="text" id="dni_edit" class="form-control">
                        <span class="input-group-text">APELLIDO</span><input type="text" id="apellido_edit" class="form-control">
                    </div>

                    <div class="input-group p-2 ">
                        <span class="input-group-text">NOMBRE</span><input type="text" id="nombre_edit" class="form-control">
                        <span class="input-group-text">CORREO ELEC.</span><input type="text" id="correo_edit" class="form-control">
                    </div>

                    <div class="input-group p-2 ">
                        <span class="input-group-text">TELEFONO</span><input type="number" step="1" id="telefono_edit" class="form-control">
                        <span class="input-group-text">NIVEL DE USUARIO</span>
                        <select id="nivel_edit" class="form-control" onchange="ActualizarCamposEdit();">
                            <option selected disabled >Seleccione Uno</option>
                            <option value="ADMINISTRADOR" >ADMINISTRADOR</option>
                            <option value="FUNCIONARIO" >FUNCIONARIO</option>
                            <option value="NIVEL 1" >NIVEL 1</option>
                            <option value="NIVEL 2" >NIVEL 2</option>
                            <option value="NIVEL 3" >NIVEL 3</option>
                        </select>
                    </div>

                    <div class="input-group p-2 ">
                        <span hidden class="input-group-text nivel_1_edit">SECRETARIA</span>
                        <select hidden id="secretaria_edit" onchange="ObtenerAreaSelectEdit()" class="form-control nivel_1_edit">
                        </select>

                        <span hidden class="input-group-text nivel_2_edit">AREA</span>
                        <select hidden id="area_edit" class="form-control nivel_2_edit">

                        </select>

                        <div hidden class="nivel_3_edit col-12">
                            <span for="indicadores_edit"  class="input-group-text">INDICADORES</span>
                            <select placeholder="Elija al menos uno" class="js-example-basic-multiple" id="indicadores_edit" multiple="multiple">
                            </select>
                        </div>
                    </div>

                    <div class="input-group p-2">
                    <button onclick="ActualizarUsuario();" type="button" class="btn btn-dark radius-5"><i class="bi bi-refresh"></i>Actualizar</button>
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
  <script src="./js/usuarios.js?v=1.3"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>


  <script>
    new PerfectScrollbar(".best-product")
 </script>


</body>

</html>
