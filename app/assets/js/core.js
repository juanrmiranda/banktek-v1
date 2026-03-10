$("#btnNavAtras").click(function (e) { history.back() });
$(".focused").focus();

$(document).ready(function ($) {

    let _fillCboOnLoad = $('.cbo-fill');
    if (_fillCboOnLoad.length !== 0) {
        poblarInputComboBoxs(_fillCboOnLoad)
        return;
    }

    let _padres = $('.cbo-padre');
    if (_padres.length !== 0) {
        PoblarCboHijos(_padres);
    }
    $('[data-toggle="tooltip"]').tooltip();
});


const poblarInputComboBoxs = async (_fillCboOnLoad) => {
    proccess = await PoblarCboOnLoad(_fillCboOnLoad);
    let _padres = $('.cbo-padre');
    if (_padres.length !== 0) {
        PoblarCboHijos(_padres);
    }

}


/**
 * 
 * @param {string} Titulo_ Mensaje a mostrar
 * @param {boolean} Error_ Si el mensaje es de error
 */
const Msgbox = (Titulo_, Error_ = false) => {
    if (Error_) {
        console.error(Titulo_);
        Titulo_ = Titulo_.split("DETAIL:")[0];
        toastr["error"](Titulo_);
        return;
    }
    toastr["success"](Titulo_);
    console.info(Titulo_);
}



/**
 * 
 * @param {string} url La URL a hacer la petición, 
 * @returns Promesa en formato JSON
 */
const peticionGet = async (url) => {
    let respuesta = null;
    respuesta = await fetch(baseurlAJAX + url);

    if (respuesta.ok === false) {
        error_ = await respuesta.text();
        error_=extractContentFetch(error_);
        console.error(error_);
        toastr["error"]('error en petición FETCH <br>' + error_);
        return Promise.reject('*** Error en respuesta');
    }

    try {
        const datos = await respuesta.json();
        return Promise.resolve(datos);
    } catch (error) {
        toastr["error"]('error en petición FETCH<br>Response NO es un objeto JSON');
        return Promise.reject('*** Error en respuesta2');
    }
}


$('.cbo-padre').bind("change", function () {
    _objeto = "#input_" + this.getAttribute('data-cbodependiente');
    _campo_codigo = this.getAttribute('data-campodependiente');
    _tabla = this.getAttribute('data-tabledependiente');
    _campo_filtro = this.getAttribute('data-campo_parent');
    _filtro = this.value;
    dbConsultaCatalogo(_objeto, _campo_codigo, _tabla, _campo_filtro, _filtro);
});


const dbConsultaCatalogo = async (Objeto, CampoCodigo, NombreTabla, CampoFiltro = null, Filtro = null, Seleccionado = null) => {
    if ($(Objeto).length == 0) {
        console.error('El comboBox: ' + Objeto + ' No existe query: ' + NombreTabla);
        return;
    }
    try {
        respuesta = await peticionGet('Generales/getCatalogo/' + NombreTabla + '/' + CampoFiltro + '/' + Filtro);

        if (respuesta.error == false) {
            addItemsComboBox(Objeto, respuesta.filas, CampoCodigo, Seleccionado);
        } else {
            Msgbox(respuesta.mensaje, true);
            console.error(respuesta.mensaje);
        }
    } catch (error) {
        console.error(error);
    }
}

const PoblarCboHijos = (Padres_) => {
    for (let i = 0; i < Padres_.length; i++) {
        _objeto = "#input_" + Padres_[i].getAttribute("data-cbodependiente");
        _campo_codigo = Padres_[i].getAttribute('data-campodependiente');
        _tabla = Padres_[i].getAttribute('data-tabledependiente');
        _campo_filtro = Padres_[i].getAttribute('data-campo_parent');
        _filtro = Padres_[i].value;
        _valor_hijo = document.querySelector(_objeto).getAttribute('data-selected');;
        if (_filtro !== '0' || _valor_hijo !== '0') {
            dbConsultaCatalogo(_objeto, _campo_codigo, _tabla, _campo_filtro, _filtro, _valor_hijo);
        }
    }
}
const PoblarCboOnLoad = async (Cbo_) => {
    for (let i = 0; i < Cbo_.length; i++) {
        _objeto = "#" + Cbo_[i].getAttribute("id");
        _tabla = Cbo_[i].getAttribute('data-table');
        _campo_codigo = Cbo_[i].getAttribute('data-campo');
        _valor = Cbo_[i].getAttribute('data-selected');
        _campo_filtro = Cbo_[i].getAttribute('data-campo_parent');
        _filtro=null;
        if (_campo_filtro !=='') {
            _filtro = Cbo_[i].getAttribute('data-value_parent');            
        } else {
            _campo_filtro=null;
        }
        if (_tabla !== '' || _valor !== '0') {
            resp = await dbConsultaCatalogo(_objeto, _campo_codigo, _tabla, _campo_filtro, _filtro, _valor);
        }
    }
}

const addItemsComboBox = (Objeto, filas, CampoCodigo, Seleccionado) => {
    $(Objeto)
        .find('option')
        .remove()
        .end()
    $.each(filas, function (i, item) {
        comboBox = document.querySelector(Objeto);
        if (Seleccionado == item[CampoCodigo]) {
            comboBox.innerHTML += `<option value="${item[CampoCodigo]}" selected="selected">${item.descripcion}</option>`;
        } else {
            comboBox.innerHTML += `<option value="${item[CampoCodigo]}">${item.descripcion}</option>`;
        }
    })
}


/**
 * 
 * @param {String} url Controller/Metodo a ejecutar
 * @param {Array} formdata_ Datos que se mandarán en el  post
 * @returns 
 */
const peticionPOST = async (url, formdata_) => {
    const respuesta = await fetch(baseurlAJAX + url, { method: "POST", body: formdata_ });
    if (respuesta.ok === false) {
        error_ = await respuesta.text();
        error_=extractContentFetch(error_);
        console.error(error_);
        Msgbox('error en petición FETCH-POST <br>' + error_);
        return Promise.reject('*** Error en respuesta');
    }

    try {
        const datos = await respuesta.json();
        return Promise.resolve(datos);
    } catch (error) {
        Msgbox('error en petición FETCH-POST<br>Response NO es un objeto JSON', true);
        return Promise.reject('*** Error en respuesta');
    }
}

// ChangeLanguage in Datatable
if ($('script[src*="jquery.datatables.min"]').length == 1) {
    $.extend(true, $.fn.dataTable.defaults, {
        "language": {
            "lengthMenu": "_MENU_ filas por página",
            "zeroRecords": "Sin coincidencias",
            "search": "Filtrar:",
            "info": "Página _PAGE_ de _PAGES_",
            "infoEmpty": "Sin registros",
            "infoFiltered": "(filtrados de _MAX_ filas)",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            },
        }
    });
}


function extractContentFetch(html) {
    _ht = new DOMParser().parseFromString(html, "text/html");
    _msj = _ht.getElementsByClassName('sigpyme-error');
    return _msj[0].innerText.trim();
}

const CambiarPass = async (Clave_) => {
    let data = new FormData();
    data.append('clave', Clave_);
    const cambioClave = await peticionPOST('Login/cambiarpassword', data);

    Msgbox(cambioClave.mensaje, cambioClave.error);

    if (cambioClave.error == false) {
        setTimeout(() => { window.location.href = baseurlAJAX+"Login/salir"; }, 1000);
    }
}

// submit QueryForm
$("#frm_query").submit(function (event) {
    event.preventDefault();
    var formElement = document.getElementById("frm_query");
    formData = new FormData(formElement);
    
    // alert('jsdjfsf');
    // if ($('#input_tipo_venta').val() == "3" && $('#input_action').val() == "new" && $('#input_comentarios').val() == "") {
    //     $("#err_msj-comentarios").text("Debe ingresar un comentario");
    // }
    // console.log(formData.get('controlador'));
    Query(formData);
});
const Query = async (Formulario_) => {
    console.log("a buscar");
    const _resultado = await peticionPOST(Formulario_.get('controlador')+'/find',Formulario_);
    console.log(_resultado);
    if (_resultado.error) {
        Msgbox(_resultado.mensaje,true);
        return;
    }
    $("#tbl-rows").empty("");
    $('#tbl-rows').html(_resultado.resultado);
}

// prevent double submit on Form
document.querySelectorAll('form.sigCrudForm').forEach(form => {
	form.addEventListener('submit', (e) => {
		if (form.classList.contains('is-submitting')) {
			e.preventDefault();
		}
		form.classList.add('is-submitting');
	});
});
// Define un objeto común con las opciones que deseas para todos los gráficos
let _graf_constant_delayed;
const noLeyendnoGridLines = {
    animation: {onComplete: () => {_graf_constant_delayed = true;},delay: (context) => {let delay = 0;if (context.type === 'data' && context.mode === 'default' && !_graf_constant_delayed) {delay = context.dataIndex * 300 + context.datasetIndex * 200;}return delay;},},
    maintainAspectRatio: false,
    tooltips: {mode: 'index',intersect: true},
    hover: {mode: 'index',intersect: true},
    plugins: {legend: {display: false,},tooltip: {callbacks: {label: function (context) {const value = context.dataset.data[context.dataIndex];return '$' + value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'); },},},},
    scales: {x: {grid: {display: false,},},y: {ticks: {callback: function (value, index, values) {if (value >= 1000) {return '$' + (value / 1000).toString() + 'k';}return '$' + value;},},grid: {display: false,},},}
};  

// bookmarks
$('#modalAddBookmark').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget)
    let url = button.data('url')
    let modal = $(this)
    modal.find('#bookmark-url').val(url)
  })
$('#btnBookmarksCreate').on('click', function () {
    let bookmarkGrupo = $('#bookmark-grupo').val();
    let bookmarkNombre = $('#bookmark-nombre').val();
    let bookmarkUrl = $('#bookmark-url').val();
    createBookmark(bookmarkGrupo,bookmarkNombre,bookmarkUrl);
});
const createBookmark = async (Grupo_,Nombre_,Bookmark_) => {
    let data = new FormData();
    data.append('grupo', Grupo_);
    data.append('nombre', Nombre_);
    data.append('bookmark', Bookmark_);
    respuesta = await peticionPOST("Bookmarks/create", data);
    Msgbox(respuesta.mensaje, respuesta.error);
    
}