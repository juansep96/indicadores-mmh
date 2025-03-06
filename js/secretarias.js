var urlBase = "./api/secretarias/";

const ObtenerSecretarias = async () => {
    InicializarTabla();
    $('#secretarias').DataTable().destroy();
    $('#secretarias').DataTable({
        'responsive': false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        dom: 'Blfrtip',
          buttons: [
              'excel', 'pdf', 'print'
          ],
        'ajax': {
            'url':urlBase+'ObtenerSecretarias'
        },
        'columns': [
          { data: 'id_secretaria' },
          { data: 'nombre_secretaria' },
          { data: 'icono_secretaria' },
          { data: 'cuando_secretaria' },    
          { data: 'quien_secretaria' },
          { data: 'acciones_secretaria' },
        ]
    });
}

const InsertarSecretaria = async () => {
    let nombre = $("#nombre").val();
    let icono = $("#icono").val();
    if(nombre){
        !icono ? icono = "bx bx-buildings" : '';
        let datos = {
            nombre,
            icono
        }
        datos = JSON.stringify(datos);
        $.post(urlBase+"InsertarSecretaria",{datos})
        .then((res)=>{
            res = JSON.parse(res);
            if(res.success){
                Lobibox.notify('success', {
					pauseDelayOnHover: true,
					continueDelayOnInactiveTab: false,
					position: 'top right',
					icon: 'bx bx-check-circle',
					msg: 'Secretaria creada con exito.',
					delay:3500
				});
                $("#modalNuevaSecretaria").modal("hide");
                $("#nombre").val('');
                $("#icono").val('');
                ObtenerSecretarias();
            }else{
                Lobibox.notify('warning', {
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    position: 'top right',
                    icon: 'bx bx-message-warning',
                    msg: 'Error al insertar. Contacte a Soporte.',
                });
            }
        })
    }else{
        Lobibox.notify('error', {
            pauseDelayOnHover: true,
            continueDelayOnInactiveTab: false,
            position: 'top right',
            icon: 'bx bx-message-error',
            msg: 'El nombre es obligatorio.',
        });
    }
}

const EditarSecretaria = async (idSecretaria) => {
    $.post(urlBase+"ObtenerSecretaria",{idSecretaria})
    .then((res)=>{
      res=JSON.parse(res);
      res.forEach((e)=>{
        $("#idSecretaria_edit").val(e.id_secretaria);
        $("#nombre_edit").val(e.nombre_secretaria.toUpperCase());
        $("#icono_edit").val(e.icono_secretaria);
      });
      $("#modalEditarSecretaria").modal('show');
    });
}

const ActualizarSecretaria = async () => {
    let idSecretaria = $("#idSecretaria_edit").val();
    let nombre = $("#nombre_edit").val();
    let icono = $("#icono_edit").val();
    if(nombre){
        !icono ? icono = "bx bx-buildings" : '';
        let datos = {
            nombre,
            icono,
            idSecretaria
        }
        datos = JSON.stringify(datos);
        $.post(urlBase+"ActualizarSecretaria",{datos})
        .then((res)=>{
            res = JSON.parse(res);
            if(res.success){
                Lobibox.notify('success', {
					pauseDelayOnHover: true,
					continueDelayOnInactiveTab: false,
					position: 'top right',
					icon: 'bx bx-check-circle',
					msg: 'Secretaria actualizada con exito.',
					delay:3500
				});
                $("#modalEditarSecretaria").modal("hide");
                ObtenerSecretarias();
            }else{
                Lobibox.notify('warning', {
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    position: 'top right',
                    icon: 'bx bx-message-warning',
                    msg: 'Error al insertar. Contacte a Soporte.',
                });
            }
        })
    }else{
        Lobibox.notify('error', {
            pauseDelayOnHover: true,
            continueDelayOnInactiveTab: false,
            position: 'top right',
            icon: 'bx bx-message-error',
            msg: 'El campo nombre es obligatorio.',
        });
    }
}

const EliminarSecretaria = async (idSecretaria) => {
    Lobibox.confirm({
        msg: "Seguro  que desea eliminar esta secretaria?",
        callback: function ($this, type, ev) {
          if(type=="yes"){
            $.post(urlBase+"EliminarSecretaria",{idSecretaria})
            .then(async(res)=>{
                res = JSON.parse(res);
                if(res.success){
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: 'Secretaria eliminada con éxito.',
                    });
                    ObtenerSecretarias();
                }else{
                    Lobibox.notify('warning', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-message-warning',
                        msg: 'Error al actualizar. Contacte a Soporte.',
                    });
                }
            });
          }else{
            Lobibox.notify('warning', {
              pauseDelayOnHover: true,
              continueDelayOnInactiveTab: false,
              position: 'top right',
              icon: 'bx bx-message-error',
              msg: 'Acción cancelada.',
            });
          }
      }
      });
}
