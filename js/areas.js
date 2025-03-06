var urlBase = "./api/areas/";

const ObtenerSecretariasSelect = async () => {
    $("#secretaria").empty();
    $("#secretaria_edit").empty();
    $.post(urlBase+"ObtenerSecretariasSelect")
    .then((data)=>{
      data=JSON.parse(data);
      data.forEach((e)=>{
          var opcion = "<option value='"+e.id_secretaria+"'>"+e.nombre_secretaria.toUpperCase()+"</option>";
        $("#secretaria").append(opcion);
        $("#secretaria_edit").append(opcion);
      })
    });
}

const ObtenerAreas = async () => {
    InicializarTabla();
    $('#areas').DataTable().destroy();
    $('#areas').DataTable({
        'responsive': false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        dom: 'Blfrtip',
          buttons: [
              'excel', 'pdf', 'print'
          ],
        'ajax': {
            'url':urlBase+'ObtenerAreas'
        },
        'columns': [
          { data: 'id_area' },
          { data: 'nombre_area' },
          { data: 'nombre_secretaria' },
          { data: 'cuando_area' },    
          { data: 'quien_area' },
          { data: 'acciones_area' },
        ]
    });
}

const InsertarArea = async () => {
    let nombre = $("#nombre").val();
    let secretaria = $("#secretaria").val();
    if(nombre && secretaria){
        let datos = {
            nombre,
            secretaria
        }
        datos = JSON.stringify(datos);
        $.post(urlBase+"InsertarArea",{datos})
        .then((res)=>{
            res = JSON.parse(res);
            if(res.success){
                Lobibox.notify('success', {
					pauseDelayOnHover: true,
					continueDelayOnInactiveTab: false,
					position: 'top right',
					icon: 'bx bx-check-circle',
					msg: 'Area creada con exito.',
					delay:3500
				});
                $("#modalNuevoArea").modal("hide");
                $("#nombre").val('');
                $("#secretaria").val('0');
                ObtenerAreas();
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
            msg: 'El nombre y secretaria es obligatorio.',
        });
    }
}

const EditarArea = async (idArea) => {
    $.post(urlBase+"ObtenerArea",{idArea})
    .then((res)=>{
      res=JSON.parse(res);
      res.forEach((e)=>{
        $("#idArea_edit").val(e.id_area);
        $("#nombre_edit").val(e.nombre_area.toUpperCase());
        $("#secretaria_edit").val(e.idSecretaria_area);
      });
      $("#modalEditarArea").modal('show');
    });
}

const ActualizarArea = async () => {
    let idArea = $("#idArea_edit").val();
    let idSecretaria = $("#secretaria_edit").val();
    let nombre = $("#nombre_edit").val();
    if(nombre && idSecretaria){
        let datos = {
            nombre,
            idSecretaria,
            idArea
        }
        datos = JSON.stringify(datos);
        $.post(urlBase+"ActualizarArea",{datos})
        .then((res)=>{
            res = JSON.parse(res);
            if(res.success){
                Lobibox.notify('success', {
					pauseDelayOnHover: true,
					continueDelayOnInactiveTab: false,
					position: 'top right',
					icon: 'bx bx-check-circle',
					msg: 'Area actualizada con exito.',
					delay:3500
				});
                $("#modalEditarArea").modal("hide");
                ObtenerAreas();
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
            msg: 'Los campos nombre y la secretaria son obligatorio.',
        });
    }
}

const EliminarArea = async (idArea) => {
    Lobibox.confirm({
        msg: "Seguro  que desea eliminar este area?",
        callback: function ($this, type, ev) {
          if(type=="yes"){
            $.post(urlBase+"EliminarArea",{idArea})
            .then(async(res)=>{
                res = JSON.parse(res);
                if(res.success){
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: 'Area eliminada con éxito.',
                    });
                    ObtenerAreas();
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
