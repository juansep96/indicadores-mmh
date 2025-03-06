let urlBase="./api/usuarios/";

$(document).ready(function() {
    $("#password").keypress(function(e) {
       if(e.which == 13) {
         restore();
       }
     });
});

async function restore(){
	let pass = $("#password").val();
    let pass2 = $("#password2").val();
    let idUsuario = $("#id_restaurar").val();
    if(pass === pass2){
        $.post(urlBase+"RegistrarBlanqueoPassword",{pass,idUsuario})
        .then(async(data)=>{
            if(data){
                data = JSON.parse(data);
                if(data.success){
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: 'Contraseña resturada con exito.',
                        delay:3500
                    });
                    setInterval(function(){window.location.href = "./index";},1500);
                }else{
                    Lobibox.notify('error', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-message-error',
                        msg: 'Error al restaurar.',
                    });
                }
            }
        });
    }else{
        Lobibox.notify('error', {
            pauseDelayOnHover: true,
            continueDelayOnInactiveTab: false,
            position: 'top right',
            icon: 'bx bx-message-error',
            msg: 'Las contraseñas no coinciden.',
        });
    }
	
}


// Usage