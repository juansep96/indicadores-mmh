var urlBase = "./api/usuarios/";

$(document).ready(function() {
    $( '#indicadores' ).select2( {
        width: '100%' ,
        closeOnSelect: true,
        dropdownParent: $('#modalNuevoUsuario')
    });
    $( '#indicadores_edit' ).select2( {
        width: '100%' ,
        closeOnSelect: true,
        dropdownParent: $('#modalEditarUsuario')
    });
});

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

const ObtenerIndicadoresSelect = async () => {
    $.post(urlBase+"ObtenerIndicadoresSelect")
    .then((data)=>{
      data=JSON.parse(data);
      data.forEach((e)=>{
          var opcion = "<option value='"+e.id_indicador+"'>"+e.nombre_indicador.toUpperCase()+"</option>";
        $("#indicadores").append(opcion);
        $("#indicadores_edit").append(opcion);
      })
    });
}

const ObtenerUsuarios = async () => {
    InicializarTabla();
    $('#usuarios').DataTable().destroy();
    $('#usuarios').DataTable({
        'responsive': false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        dom: 'Blfrtip',
          buttons: [
              'excel', 'pdf', 'print'
          ],
        'ajax': {
            'url':urlBase+'ObtenerUsuarios'
        },
        'columns': [
          { data: 'dni_usuario' },
          { data: 'apellido_usuario' },
          { data: 'nombre_usuario' },
          { data: 'nivel_usuario' },    
          { data: 'nombre_secretaria' },
          { data: 'nombre_area' },
          { data: 'acciones_usuario' },
        ]
    });
}

const ActualizarCampos = async () => {
    let nivel = $("#nivel").val();
    $(".nivel_1").each(function(index){
        element = $(this); // <-- en la variable element tienes tu elemento
        element.attr('hidden', true);
    });
    $(".nivel_2").each(function(index){
        element = $(this); // <-- en la variable element tienes tu elemento
        element.attr('hidden', true);
    });
    $(".nivel_3").each(function(index){
        element = $(this); // <-- en la variable element tienes tu elemento
        element.attr('hidden', true);
    });
    switch (nivel) {
        case "NIVEL 1":
            //Tenemos que setear solo una secretaria sin area
            $(".nivel_1").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
            });
        break;
        case "NIVEL 2":
            //Tenemos que setear secretaria y area
            $(".nivel_1").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
            });
            $(".nivel_2").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
            });
        break;
        case "NIVEL 3":
            //Tenemos que secretar solo ids de indicadores
            $(".nivel_3").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
            });
        break;
    
    }
}


const ActualizarCamposEdit = async () => {
    let nivel = $("#nivel_edit").val();
    $(".nivel_1_edit").each(function(index){
        element = $(this); // <-- en la variable element tienes tu elemento
        element.attr('hidden', true);
    });
    $(".nivel_2_edit").each(function(index){
        element = $(this); // <-- en la variable element tienes tu elemento
        element.attr('hidden', true);
    });
    $(".nivel_3_edit").each(function(index){
        element = $(this); // <-- en la variable element tienes tu elemento
        element.attr('hidden', true);
    });
    switch (nivel) {
        case "NIVEL 1":
            //Tenemos que setear solo una secretaria sin area
            $(".nivel_1_edit").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
            });
        break;
        case "NIVEL 2":
            //Tenemos que setear secretaria y area
            $(".nivel_1_edit").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
            });
            $(".nivel_2_edit").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
            });
        break;
        case "NIVEL 3":
            //Tenemos que secretar solo ids de indicadores
            $(".nivel_3_edit").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
            });
        break;
    
    }
}

const InsertarUsuario = async () => {
    let nombre = $("#nombre").val();
    let apellido = $("#apellido").val();
    let dni = $("#dni").val();
    let correo = $("#correo").val();
    let telefono = $("#telefono").val();
    let nivel = $("#nivel").val();
    let password = $("#password").val();
    let password2 = $("#password2").val();
    let secretaria = $("#secretaria").val();
    let area = $("#area").val();
    let indicadores = $("#indicadores").val();
    if(nombre && apellido && dni && nivel && password && password2){
        if(password === password2){
            if(nivel == "NIVEL 3"){
                secretaria = null;
                area = null;
                if(indicadores.length>0){
                    let datos = {
                        nombre,
                        apellido,
                        dni,
                        correo,
                        telefono,
                        nivel,
                        password,
                        secretaria,
                        area,
                        indicadores : JSON.stringify(indicadores)
                    }
                    datos = JSON.stringify(datos);
                    $.post(urlBase+"InsertarUsuario",{datos})
                    .then((res)=>{
                        res = JSON.parse(res);
                        if(res.success){
                            Lobibox.notify('success', {
                                pauseDelayOnHover: true,
                                continueDelayOnInactiveTab: false,
                                position: 'top right',
                                icon: 'bx bx-check-circle',
                                msg: 'Usuario creado con exito.',
                                delay:3500
                            });
                            $("#modalNuevoUsuario").modal("hide");
                            $("#nombre").val('');
                            $("#apellido").val('');
                            $("#dni").val('');
                            $("#correo").val('');
                            $("#telefono").val('');
                            $("#nivel").val('0');
                            $("#password").val('');
                            $("#password2").val('');
                            $("#secretaria").val('0');
                            $("#area").val('0');
                            $("#indicadores").val('0');
                            ObtenerUsuarios();
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
                        msg: 'Debes seleccionar al menos un indicador.',
                    }); 
                }
            }else{
                let datos = {
                    nombre,
                    apellido,
                    dni,
                    correo,
                    telefono,
                    nivel,
                    password,
                    secretaria,
                    area,
                    indicadores : JSON.stringify(indicadores)
                }
                datos = JSON.stringify(datos);
                $.post(urlBase+"InsertarUsuario",{datos})
                .then((res)=>{
                    res = JSON.parse(res);
                    if(res.success){
                        Lobibox.notify('success', {
                            pauseDelayOnHover: true,
                            continueDelayOnInactiveTab: false,
                            position: 'top right',
                            icon: 'bx bx-check-circle',
                            msg: 'Usuario creado con exito.',
                            delay:3500
                        });
                        $("#modalNuevoUsuario").modal("hide");
                        $("#nombre").val('');
                        $("#apellido").val('');
                        $("#dni").val('');
                        $("#correo").val('');
                        $("#telefono").val('');
                        $("#nivel").val('0');
                        $("#password").val('');
                        $("#password2").val('');
                        $("#secretaria").val('0');
                        $("#area").val('0');
                        $("#indicadores").val('0');
                        ObtenerUsuarios();
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
            }
            
        }else{
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                icon: 'bx bx-message-error',
                msg: 'Las contrasenas no coinciden.',
            });
        }
    }else{
        Lobibox.notify('error', {
            pauseDelayOnHover: true,
            continueDelayOnInactiveTab: false,
            position: 'top right',
            icon: 'bx bx-message-error',
            msg: 'Complete todos los campos obligatorios.',
        });
    }
}

const AbrirUsuario = async (idUsuario) => {
    $(".nivel_1").each(function(index){
        element = $(this); // <-- en la variable element tienes tu elemento
        element.attr('hidden', true);
    });
    $(".nivel_2").each(function(index){
        element = $(this); // <-- en la variable element tienes tu elemento
        element.attr('hidden', true);
    });
    $(".nivel_3").each(function(index){
        element = $(this); // <-- en la variable element tienes tu elemento
        element.attr('hidden', true);
    });
    $.post(urlBase+"ObtenerUsuario",{idUsuario})
    .then((res)=>{
      res=JSON.parse(res);
      res.forEach((e)=>{
        $("#dni_view").val(e.dni_usuario);
        $("#nombre_view").val(e.nombre_usuario.toUpperCase());
        $("#apellido_view").val(e.apellido_usuario.toUpperCase());
        e.correo_usuario ? $("#correo_view").val(e.correo_usuario) : '';
        e.telefono_usuario ? $("#telefono_view").val(e.telefono_usuario) : '';
        $("#nivel_view").val(e.nivel_usuario);
       switch (e.nivel_usuario) {
        case "NIVEL 1":
            //Tenemos que setear solo una secretaria sin area
            $(".nivel_1").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
            });
            $("#secretaria_view").val(e.nombre_secretaria.toUpperCase())
        break;
        case "NIVEL 2":
            //Tenemos que setear secretaria y area
            $(".nivel_1").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
            });
            $(".nivel_2").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
            });
            $("#secretaria_view").val(e.nombre_secretaria.toUpperCase())
            $("#area_view").val(e.nombre_area.toUpperCase())
        break;
        case "NIVEL 3":
            //Tenemos que secretar solo ids de indicadores
            $(".nivel_3").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
            });
        break;
       }
       $("#modalVerUsuario").modal('show');
      });
    });
}

const EditarUsuario = async (idUsuario) => {
    $("#indicadores_edit").val('0');
    $(".nivel_1_edit").each(function(index){
        element = $(this); // <-- en la variable element tienes tu elemento
        element.attr('hidden', true);
    });
    $(".nivel_2_edit").each(function(index){
        element = $(this); // <-- en la variable element tienes tu elemento
        element.attr('hidden', true);
    });
    $(".nivel_3_edit").each(function(index){
        element = $(this); // <-- en la variable element tienes tu elemento
        element.attr('hidden', true);
    });
    $.post(urlBase+"ObtenerUsuario",{idUsuario})
    .then((res)=>{
      res=JSON.parse(res);
      res.forEach((e)=>{
        $("#idUsuario_edit").val(e.id_usuario);
        $("#dni_edit").val(e.dni_usuario);
        $("#nombre_edit").val(e.nombre_usuario.toUpperCase());
        $("#apellido_edit").val(e.apellido_usuario.toUpperCase());
        e.correo_usuario ? $("#correo_edit").val(e.correo_usuario) : $("#correo_edit").val('');
        e.telefono_usuario ? $("#telefono_edit").val(e.telefono_usuario) : $("#telefono_edit").val('') ;
        $("#nivel_edit").val(e.nivel_usuario);
       switch (e.nivel_usuario) {
        case "NIVEL 1":
            //Tenemos que setear solo una secretaria sin area
            $(".nivel_1_edit").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
            });
            $("#secretaria_edit").val(e.idSecretaria_usuario)
        break;
        case "NIVEL 2":
            //Tenemos que setear secretaria y area
            $(".nivel_1_edit").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
            });
            $(".nivel_2_edit").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
            });
            $("#secretaria_edit").val(e.idSecretaria_usuario);
            ObtenerAreaSelectEdit();
            setTimeout(() => {
                $("#area_edit").val(e.idArea_usuario);
            }, 500);
        break;
        case "NIVEL 3":
            //Tenemos que setear solo ids de indicadores
            $(".nivel_3_edit").each(function(index){
                element = $(this); // <-- en la variable element tienes tu elemento
                element.removeAttr('hidden');
                e.idIndicadores_usuario = eval(e.idIndicadores_usuario);
                console.log(e.idIndicadores_usuario);
                e.idIndicadores_usuario.forEach((e)=>{
                    $("#indicadores_edit").val(e);
                    $('#indicadores_edit').trigger('change');
                })
            });
        break;
       }
       $("#modalEditarUsuario").modal('show');
      });
    });
}

const ActualizarUsuario = async () => {
    let idUsuario = $("#idUsuario_edit").val();
    let nombre = $("#nombre_edit").val();
    let apellido = $("#apellido_edit").val();
    let dni = $("#dni_edit").val();
    let correo = $("#correo_edit").val();
    let telefono = $("#telefono_edit").val();
    let nivel = $("#nivel_edit").val();
    let secretaria = $("#secretaria_edit").val();
    let area = $("#area_edit").val();
    let indicadores = $("#indicadores_edit").val();
    if(nombre && apellido && dni && nivel){
        if(nivel == "NIVEL 3"){
            secretaria = null;
            area = null;
            if(indicadores.length >0){
                let datos = {
                    nombre,
                    apellido,
                    dni,
                    correo,
                    telefono,
                    nivel,
                    secretaria,
                    area,
                    indicadores : JSON.stringify(indicadores),
                    idUsuario
                }
                datos = JSON.stringify(datos);
                $.post(urlBase+"ActualizarUsuario",{datos})
                .then((res)=>{
                    res = JSON.parse(res);
                    if(res.success){
                        Lobibox.notify('success', {
                            pauseDelayOnHover: true,
                            continueDelayOnInactiveTab: false,
                            position: 'top right',
                            icon: 'bx bx-check-circle',
                            msg: 'Usuario actualizado con exito.',
                            delay:3500
                        });
                        $("#modalEditarUsuario").modal("hide");
                        ObtenerUsuarios();
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
                    msg: 'Debe seleccionar al menos un indicador.',
                });
            }
        }else{
            let datos = {
                nombre,
                apellido,
                dni,
                correo,
                telefono,
                nivel,
                secretaria,
                area,
                indicadores : JSON.stringify(indicadores),
                idUsuario
            }
            datos = JSON.stringify(datos);
            $.post(urlBase+"ActualizarUsuario",{datos})
            .then((res)=>{
                res = JSON.parse(res);
                if(res.success){
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: 'Usuario actualizado con exito.',
                        delay:3500
                    });
                    $("#modalEditarUsuario").modal("hide");
                    ObtenerUsuarios();
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
        }
    }else{
        Lobibox.notify('error', {
            pauseDelayOnHover: true,
            continueDelayOnInactiveTab: false,
            position: 'top right',
            icon: 'bx bx-message-error',
            msg: 'Complete todos los campos obligatorios.',
        });
    }
}

const EliminarUsuario = async (idUsuario) => {
    Lobibox.confirm({
        msg: "Seguro  que desea eliminar este usuario?",
        callback: function ($this, type, ev) {
          if(type=="yes"){
            $.post(urlBase+"EliminarUsuario",{idUsuario})
            .then(async(res)=>{
                res = JSON.parse(res);
                if(res.success){
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: 'Usuario eliminado con éxito.',
                    });
                    ObtenerUsuarios();
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

const RestaurarPassword = async (idUsuario) => {
    Lobibox.confirm({
        msg: "Seguro  que desea restaurar la contraseña del usuario?",
        callback: function ($this, type, ev) {
          if(type=="yes"){
            $.post(urlBase+"RestaurarPassword",{idUsuario})
            .then(async(res)=>{
                res = JSON.parse(res);
                if(res.success){
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: 'Se envio con exito el correo de restauracion',
                    });
                }else{
                    Lobibox.notify('warning', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-message-warning',
                        timer:1500,
                        msg: 'Error al recuperar. Compruebe que el usuario contenga un correo electronico valido.',
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