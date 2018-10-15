// Cargar recien cuando el documento esta listo
$(document).ready(function() {
    $("#categoria").click(addNewCategoria);
    $("#escalada").click();
    $("#abierta").click();
    $("#seleccion").click();
});

var newCategoriaButtons = '\
		<tbody>\
			<tr>\
				<td width="25%"><button id="categoria">+ Categoria</button></td>\
				<td width="25%"><button id="escalada">+ Con Escala</button></td>\
				<td width="25%"><button id="abierta">+ Abierta</button></td>\
				<td width="25%"><button id="seleccion">+ Seleccion Multiple</button><td>\
			</tr>\
		</tbody>\
	</table>';

var newCategoria = '\
	<table>\
		<thead>\
			<tr>\
				<th colspan="4">Nombre de la categoria creada</th>\
			</tr>\
		</thead>' + newCategoriaButtons;

var addNewCategoria = function() {
    var estructura = $("#enc-estructura");
    var estructuraContenido = estructura.html();
    estructuraContenido += newCategoria;
    estructura.html(estructuraContenido);
};

/* *********************************************************************************
********************************* Ejemplo de AJAX **********************************
********************************************************************************* */
var updatePage = function(resp) {
	$('#target').html(resp.people[0].name);
};

var printError = function(req, status, err) {
	console.log('something went wrong', status, err);
};

var ajaxOptions = {
	url: '/data/people.json',
	dataType: 'json',
	success: updatePage,
	error: printError
};

$.ajax(ajaxOptions);