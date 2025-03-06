var urlBase = "./api/mensajes/";

const ObtenerMensajes = async () => {
    InicializarTabla();
    $('#mensajes').DataTable().destroy();
    $('#mensajes').DataTable({
        'responsive': false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        dom: 'Blfrtip',
          buttons: [
              'excel', 'pdf', 'print'
          ],
        'ajax': {
            'url':urlBase+'ObtenerMensajes'
        },
        'columns': [
          { data: 'titulo_mensaje' },
          { data: 'detalle_mensaje' },
          { data: 'cuando_mensaje' },
          { data: 'quien_mensaje' },    
          { data: 'acciones_mensaje' },
        ]
    });
}

const InsertarMensaje = async () => {
    let titulo = $("#titulo").val();
    let detalle = $("#detalle").val();
    if(titulo){
        let datos = {
            titulo,
            detalle
        }
        datos = JSON.stringify(datos);
        $.post(urlBase+"InsertarMensaje",{datos})
        .then((res)=>{
            res = JSON.parse(res);
            if(res.success){
                Lobibox.notify('success', {
					pauseDelayOnHover: true,
					continueDelayOnInactiveTab: false,
					position: 'top right',
					icon: 'bx bx-check-circle',
					msg: 'Mensaje creado con exito.',
					delay:3500
				});
                $("#modalNuevoMensaje").modal("hide");
                $("#titulo").val('');
                $("#detalle").val('');
                ObtenerMensajes();
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
            msg: 'El titulo es obligatorio.',
        });
    }
}

const EditarMensaje = async (idMensaje) => {
    $.post(urlBase+"ObtenerMensaje",{idMensaje})
    .then((res)=>{
      res=JSON.parse(res);
      res.forEach((e)=>{
        $("#idMensaje_edit").val(e.id_mensaje);
        $("#titulo_edit").val(e.titulo_mensaje);
        e.detalle_mensaje ? $("#detalle_edit").val(e.detalle_mensaje) : $("#detalle_edit").val('');
      });
      $("#modalEditarMensaje").modal('show');
    });
}

const ActualizarMensaje = async () => {
    let idMensaje = $("#idMensaje_edit").val();
    let titulo = $("#titulo_edit").val();
    let detalle = $("#detalle_edit").val();
    if(titulo){
        let datos = {
            titulo,
            detalle,
            idMensaje
        }
        datos = JSON.stringify(datos);
        $.post(urlBase+"ActualizarMensaje",{datos})
        .then((res)=>{
            res = JSON.parse(res);
            if(res.success){
                Lobibox.notify('success', {
					pauseDelayOnHover: true,
					continueDelayOnInactiveTab: false,
					position: 'top right',
					icon: 'bx bx-check-circle',
					msg: 'Mensaje actualizado con exito.',
					delay:3500
				});
                $("#modalEditarMensaje").modal("hide");
                ObtenerMensajes();
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
            msg: 'El campo titulo es obligatorio.',
        });
    }
}

const EliminarMensaje = async (idMensaje) => {
    Lobibox.confirm({
        msg: "Seguro  que desea eliminar este mensaje?",
        callback: function ($this, type, ev) {
          if(type=="yes"){
            $.post(urlBase+"EliminarMensaje",{idMensaje})
            .then(async(res)=>{
                res = JSON.parse(res);
                if(res.success){
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: 'Mensaje eliminado con éxito.',
                    });
                    ObtenerMensajes();
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
