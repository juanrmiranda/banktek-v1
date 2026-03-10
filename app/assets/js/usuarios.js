$("#btnReiniciarClave").click(function (e) {
    ReiniciarClave();
});



const ReiniciarClave = async () => {
    _uuid = $('#input_uuid').val();
    try {
        respuesta = await peticionGet('Usuarios/reiniciar_clave/' + _uuid);        
        Msgbox(respuesta.mensaje, respuesta.error);
    } catch (error) {
        console.error(error);
    }
}

// DIRECCIONES DE DESPACHO COPIA DE CLIENTE
$('#input_rol').change(function () {
    _rol = $('#input_rol').val();
    CambiarRolSeguridad(_rol);
});
const CambiarRolSeguridad = async (Rol_) => {
    let data = new FormData();
    _uuid = $('#input_uuid').val();
    data.append('rol', Rol_);
    data.append('uuid', _uuid);
    respuesta = await peticionPOST("Usuarios/cambiar_rol_seguridad", data);
    Msgbox(respuesta.mensaje, respuesta.error);
}

$('[data-mask]').inputmask();



