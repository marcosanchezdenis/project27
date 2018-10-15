$(document).ready(function () {
	// Muestra el boton de busqueda
	$('#search-box a').html('Buscar <span class="flecha-dir">&darr;</span>');
	$("#search-box a").css("display", "block");

	$("#search-box a").click(function (event) {
		event.preventDefault;
		if ($(".search-form").css("display") == "block") {
			$(".search-form").css("display", "none");
			$(".flecha-dir").html("&darr;");
		} else {
			$(".search-form").css("display", "block");
			$(".flecha-dir").html("&uarr;");
		}
	});
	
	$(".delete-button").click(function (event) {
		event.preventDefault;
		$preventionMsg = "Esta seguro que desea borrar la plantilla?\nUna vez borrada no podr\u00e1 restaurarla.";
		if (!confirm($preventionMsg)) {
			return false;
		}
	});
	
	$(".activar-button").click(function (event) {
		event.preventDefault;
		$preventionMsg = "Esta seguro que desea activar la plantilla?\nUna vez activa podr\u00e1 usarse para cualquier encuesta.";
		if (!confirm($preventionMsg)) {
			return false;
		}
	});
});