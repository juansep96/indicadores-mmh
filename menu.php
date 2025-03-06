<?php
  session_start();
?>
<script>
  $.post("./api/ObtenerSesion")
    .then((data)=>{
      data = JSON.parse(data);
      if( (data.nivel_usuario=="ADMINISTRADOR")){
        $("#menuPrincipal").prop('hidden',false);
      }else{
        $("#menuUsuario").prop('hidden',false);
      }
    })
</script>
<div id="admin">
    <div class="d-none d-sm-none d-md-block">
        <header class="top-header">
            <nav class="navbar navbar-expand gap-3 row p-3">
            <div class="col-4 text-center p-3">
                <h4 id="mensaje"><?php echo strtoupper($_SESSION['apellido_usuario'] .' '.$_SESSION['nombre_usuario']);?></h4>
            </div>
            <div class="col-4 text-center p-3">
                <h4 id="role"><?php echo strtoupper($_SESSION['nivel_usuario']);?></h4>
            </div>
            <div class="col-4 text-center p-3">
                <h4 id="sucursal">
                  <?php 
                    if($_SESSION['nivel_usuario'] == "ADMINISTRADOR" || $_SESSION['nivel_usuario'] == "FUNCIONARIO"){
                      echo "TODAS HAB.";
                    }else{
                      echo strtoupper($_SESSION['nombre_secretaria']);
                    }
                  ?>
                </h4>
            </div>
            </nav>
        </header>
        <aside hidden id="menuPrincipal" class="sidebar-wrapper" data-simplebar="true">
          <div class="sidebar-header">
            <div>
              <h4 class="logo-text">Panel de Admin.</h4>
            </div>
            <div class="toggle-icon ms-auto"> <i class="bi bi-list"></i>
            </div>
          </div>
          <ul class="metismenu" id="admin">
            <li>
              <a href="inicio">
                <div class="parent-icon"><i class="bi bi-house-fill"></i>
                </div>
                <div class="menu-title">Inicio</div>
              </a>
            </li>

            <li>
              <a href="secretarias">
                <div class="parent-icon"><i class="bi bi-building"></i>
                </div>
                <div class="menu-title">Secretarias</div>
              </a>
            </li>

            <li>
              <a href="areas">
                <div class="parent-icon"><i class="bi bi-diagram-2"></i>
                </div>
                <div class="menu-title">Areas</div>
              </a>
            </li>

            <li>
              <a href="indicadores">
                <div class="parent-icon"><i class="bi bi-bar-chart"></i>
                </div>
                <div class="menu-title">Indicadores</div>
              </a>
            </li>

            <li>
              <a href="usuarios">
                <div class="parent-icon"><i class="bx bx-user"></i>
                </div>
                <div class="menu-title">Usuarios</div>
              </a>
            </li>

            <li>
              <a href="auditorias">
                <div class="parent-icon"><i class="bi bi-list"></i>
                </div>
                <div class="menu-title">Auditorias</div>
              </a>
            </li>

            <li>
              <a href="mensajes">
                <div class="parent-icon"><i class="bx bx-cog"></i>
                </div>
                <div class="menu-title">Mensajes</div>
              </a>
            </li>

            <li>
              <a href="checkout">
                <div class="parent-icon"><i class="bx bx-user-x"></i>
                </div>
                <div class="menu-title">Cerrar Sesión</div>
              </a>
            </li>

          </ul>

        </aside>

        <aside hidden id="menuUsuario" class="sidebar-wrapper" data-simplebar="true">
          <div class="sidebar-header">
            <div>
              <h4 class="logo-text">Panel de Usuario</h4>
            </div>
            <div class="toggle-icon ms-auto"> <i class="bi bi-list"></i>
            </div>
          </div>
          <ul class="metismenu" id="admin">
            <li>
              <a href="inicio">
                <div class="parent-icon"><i class="bi bi-house-fill"></i>
                </div>
                <div class="menu-title">Inicio</div>
              </a>
            </li>

            <li>
              <a href="checkout">
                <div class="parent-icon"><i class="bx bx-user-x"></i>
                </div>
                <div class="menu-title">Cerrar Sesión</div>
              </a>
            </li>

          </ul>

        </aside>
  </div>
</div>


<script src="./assets/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="./assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>