var urlBase = "./api/indicadores/";

var arrayAreas = [];
var arrayEditarAreas = [];
const ObtenerSecretariasSelect = async () => {
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

const ObtenerAreasSelect = async () => {
    let idSecretaria = $("#secretaria").val();
    $("#area").empty();
    $.post(urlBase+"ObtenerAreasSelect",{idSecretaria})
    .then((data)=>{
      data=JSON.parse(data);
      data.forEach((e)=>{
          var opcion = "<option value='"+e.id_area+"'>"+e.nombre_area.toUpperCase()+"</option>";
        $("#area").append(opcion);
      })
    });
}

const ObtenerAreaSelectEdit = async () => {
    idSecretaria = $("#secretaria_edit").val();
    $("#area_edit").empty();
    $.post(urlBase+"ObtenerAreasSelect",{idSecretaria})
    .then((data)=>{
      data=JSON.parse(data);
      data.forEach((e)=>{
          var opcion = "<option value='"+e.id_area+"'>"+e.nombre_area.toUpperCase()+"</option>";
        $("#area_edit").append(opcion);
      })
    });
}

const ObtenerIndicadores = async () => {
    InicializarTabla();
    $('#indicadores').DataTable().destroy();
    $('#indicadores').DataTable({
        'responsive': false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        dom: 'Blfrtip',
          buttons: [
              'excel', 'pdf', 'print'
          ],
        'ajax': {
            'url':urlBase+'ObtenerIndicadores'
        },
        'columns': [
          { data: 'nombre_indicador' },
          { data: 'url_indicador' },    
          { data: 'cuando_indicador' },
          { data: 'quien_indicador' },
          { data: 'acciones_indicador' },
        ]
    });
}

const AgregarArea =  async () => {
    let area = $("#area").val();
    let secretaria = $("#secretaria").val();
    if(area){
        let obj = {
            idArea:area,
            idSecretaria:secretaria,
            secretaria: $("#secretaria").find('option:selected').text(),
            area:$("#area").find('option:selected').text(),
        }
        arrayAreas.push(obj);
        ActualizarAreasTabla();
        $("#secretaria").val("0");
        ObtenerAreasSelect();
    }else{
        Lobibox.notify('warning', {
            pauseDelayOnHover: true,
            continueDelayOnInactiveTab: false,
            position: 'top right',
            icon: 'bx bx-message-warning',
            msg: 'Debe seleccionar un area al menos.',
        });
    }
}

const ActualizarAreasTabla = async () => {
    $('.filaAreas').remove();
    if(arrayAreas.length>0){
        arrayAreas.forEach((e)=>{
            accion = '<a href="javascript:;" onclick="DesvincularArea('+e.idArea+')" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Delete" aria-label="Eliminar"><i class="bi bi-trash-fill"></i></a>';
            html = "<tr class='filaAreas'><td>"+e.secretaria+"</td><td>"+e.area+"</td><td>"+accion+"</td></tr>";
            $("#areas tbody").append(html);
        })
    }
}

const DesvincularArea = async (idArea) => {
    arrayAreas = arrayAreas.filter(function(el){
        return el.idArea != idArea
     });
     ActualizarAreasTabla();
}

const InsertarIndicador = async () => {
    let nombre = $("#nombre").val();
    let url = $("#url").val();
    let detalle = $("#detalle").val();
    if(nombre && url && arrayAreas.length>0){
        let datos = {
            nombre,
            url,
            detalle,
            arrayAreas : JSON.stringify(arrayAreas)
        }
        datos = JSON.stringify(datos);
        $.post(urlBase+"InsertarIndicador",{datos})
        .then((res)=>{
            res = JSON.parse(res);
            if(res.success){
                Lobibox.notify('success', {
					pauseDelayOnHover: true,
					continueDelayOnInactiveTab: false,
					position: 'top right',
					icon: 'bx bx-check-circle',
					msg: 'Indicador creado con exito.',
					delay:3500
				});
                $("#modalNuevoIndicador").modal("hide");
                $("#nombre").val('');
                $("#url").val('');
                $("#detalle").val('');
                arrayAreas = [];
                ObtenerIndicadores();
                ActualizarAreasTabla();
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
            msg: 'Debe indicar un nombre y al menos un area habilitada.',
        });
    }
}

const AbrirIndicador = async (idIndicador) => {
    $.post(urlBase+"ObtenerIndicador",{idIndicador})
    .then((res)=>{
      res=JSON.parse(res);
      res.forEach((e)=>{
        e.url_indicador ? window.open(e.url_indicador,'_blank') : '';
      });
    });
}


const VerIndicador = async (idIndicador) => {
    $.post(urlBase+"ObtenerIndicador",{idIndicador})
    .then(async (res)=>{
        res=JSON.parse(res);
        indicadorData = JSON.parse(res.data);
        indicadorData = indicadorData[0];
        habilitaciones = JSON.parse(res.habilitaciones);
        $("#nombre_ver").val(indicadorData.nombre_indicador);
        indicadorData.detalle_indicador ? $("#detalle_ver").val(indicadorData.detalle_indicador) : $("#detalle_ver").val('') ;
        $("#url_ver").val(indicadorData.url_indicador);
        $('.filasAreas_ver').remove();
        habilitaciones.forEach((e)=>{
            html = "<tr class='filasAreas_ver'><td>"+e.nombre_secretaria.toUpperCase()+"</td><td>"+e.nombre_area.toUpperCase()+"</td><td>"+e.apellido_usuario.toUpperCase()+ ' ' + e.nombre_usuario.toUpperCase()+"</td><td>"+moment(e.cuando_relacion).format('DD/MM/YYYY HH:ss')+"</td></tr>";
            $("#areas_ver tbody").append(html);
        });

        $("#modalVerIndicador").modal('show');
    });
}

const ActualizarAreasTablaEdit = async () => {
    $('.filaAreasEdit').remove();
    if(arrayEditarAreas.length>0){
        arrayEditarAreas.forEach((e)=>{
            accion = '<a href="javascript:;" onclick="DesvincularRelacion('+e.id_relacion+')" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Delete" aria-label="Eliminar"><i class="bi bi-trash-fill"></i></a>';
            html = "<tr class='filaAreasEdit'><td>"+e.nombre_secretaria.toUpperCase()+"</td><td>"+e.nombre_area.toUpperCase()+"</td><td>"+e.apellido_usuario.toUpperCase()+ ' ' + e.nombre_usuario.toUpperCase()+"</td><td>"+moment(e.cuando_relacion).format('DD/MM/YYYY HH:ss')+"</td><td>"+accion+"</td></tr>";
            $("#areas_edit tbody").append(html);
        })
    }
}


const EditarIndicador = async (idIndicador) => {
    $.post(urlBase+"ObtenerIndicador",{idIndicador})
    .then(async (res)=>{
        res=JSON.parse(res);
        indicadorData = JSON.parse(res.data);
        indicadorData = indicadorData[0];
        habilitaciones = JSON.parse(res.habilitaciones);
        $("#idIndicador_edit").val(indicadorData.id_indicador);
        $("#nombre_edit").val(indicadorData.nombre_indicador);
        $("#url_edit").val(indicadorData.url_indicador);
        indicadorData.detalle_indicador ? $("#detalle_edit").val(indicadorData.detalle_indicador) : $("#detalle_edit").val('');
        $("#secretaria_edit").val('0');
        arrayEditarAreas = habilitaciones;
        ActualizarAreasTablaEdit();
        $("#modalEditarIndicador").modal('show');
    });
}

const ActualizarIndicador = async () => {
    let idIndicador = $("#idIndicador_edit").val();
    let nombre = $("#nombre_edit").val();
    let url = $("#url_edit").val();
    let detalle = $("#detalle_edit").val();
    if(nombre && url){
        let datos = {
            idIndicador,
            nombre,
            url,
            detalle
        }
        datos = JSON.stringify(datos);
        $.post(urlBase+"ActualizarIndicador",{datos})
        .then((res)=>{
            res = JSON.parse(res);
            if(res.success){
                Lobibox.notify('success', {
					pauseDelayOnHover: true,
					continueDelayOnInactiveTab: false,
					position: 'top right',
					icon: 'bx bx-check-circle',
					msg: 'Indicador actualizado con exito.',
					delay:3500
				});
                $("#modalEditarIndicador").modal("hide");
                ObtenerIndicadores();
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

const EliminarIndicador = async (idIndicador) => {
    Lobibox.confirm({
        msg: "Seguro  que desea eliminar este indicador?",
        callback: function ($this, type, ev) {
          if(type=="yes"){
            $.post(urlBase+"EliminarIndicador",{idIndicador})
            .then(async(res)=>{
                res = JSON.parse(res);
                if(res.success){
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: 'Indicador eliminado con éxito.',
                    });
                    ObtenerIndicadores();
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

const DesvincularRelacion = async (idRelacion) => {
    Lobibox.confirm({
        msg: "Seguro  que desea desvincular el area?",
        callback: function ($this, type, ev) {
          if(type=="yes"){
            $.post(urlBase+"EliminarRelacion",{idRelacion})
            .then(async(res)=>{
                res = JSON.parse(res);
                if(res.success){
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: 'Desvinculacion realizada con éxito.',
                    });
                    arrayEditarAreas = JSON.parse(res.data);
                    ActualizarAreasTablaEdit();
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

const AgregarAreaEdit =  async () => {
    let area = $("#area_edit").val();
    let secretaria = $("#secretaria_edit").val();
    let idIndicador = $("#idIndicador_edit").val();
    if(area){
        let obj = {
            idArea:area,
            idSecretaria:secretaria,
            idIndicador
        }
        let data = JSON.stringify(obj)
        $.post(urlBase+"RegistrarRelacion",{data})
        .then((res)=>{
            res = JSON.parse(res);
                if(res.success){
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: 'Vinculacion realizada con éxito.',
                    });
                    arrayEditarAreas = JSON.parse(res.data);
                    ActualizarAreasTablaEdit();
                }else{
                    Lobibox.notify('warning', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-message-warning',
                        msg: 'Error al registrar. Contacte a Soporte.',
                    });
                }
        })
    }else{
        Lobibox.notify('warning', {
            pauseDelayOnHover: true,
            continueDelayOnInactiveTab: false,
            position: 'top right',
            icon: 'bx bx-message-warning',
            msg: 'Debe seleccionar un area al menos.',
        });
    }
}

