$(document).ready(function () {
	var opciones = 0;
	
	$('.add-categoria').button().click(function (event) {
		event.preventDefault ? event.preventDefault : event.returnValue = false;
		
		// Sacar datos del boton
		tipoElemento = $(this).attr('tipo');
		padre_id = $(this).attr('padre_id');
		padre = $(this).attr('padre');
		
		// Agregar Padre al Form
		$(".add-categoria-padre").html('La categoria ser&aacute; agregada a la ' + tipoElemento + ':<br />"' + padre.toUpperCase() + '"');
		$("#add-categoria-form").find(".padre-id").attr("value", padre_id);
		$("#add-categoria-form").find(".padre").attr("value", tipoElemento);
		
		$("#add-categoria-form").find(".categoria_id").attr("value", "");
		$("#add-categoria-form").find("#nombre").attr("value", "");
		$("#add-categoria-form").find("#descripcion").attr("value", "");
		
		// Abrir el formulario
		$("#add-categoria-dialog").dialog("open");
	});
	
	$('.edit-categoria').click(function (event) {
		event.preventDefault ? event.preventDefault : event.returnValue = false;
		
		data = $(this).attr('id').split('&');
		cat_id = data[0].split('=');
		tipoElemento = data[1].split('=');
		padre_id = data[2].split('=');
		
		$.ajax({
			url: 'get-categoria?id=' + cat_id[1],
			success: function (data) {
				$(".add-categoria-padre").html('');
				$("#add-categoria-form").find(".padre-id").attr("value", padre_id[1]);
				$("#add-categoria-form").find(".padre").attr("value", tipoElemento[1]);
				$("#add-categoria-form").find(".categoria_id").attr("value", cat_id[1]);
				
				$("#add-categoria-form").find("#nombre").attr("value", data["nombre"]);
				$("#add-categoria-form").find("#descripcion").attr("value", data["descripcion"]);
				
				$("#add-categoria-dialog").dialog("open");
			}
		});
	});
	
	$("#add-categoria-dialog").dialog({
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		modal: true,
		buttons: {
			"Guardar Categoria": function() {
				var nombre = $("#add-categoria-form").find("#nombre").attr("value");
				if (nombre != "" && nombre != null) {
					$("#add-categoria-form").submit();
				} else {
					alert("El campo 'Categoria' es obligatorio!");
				}
			},
			Cancelar: function() {
				$(this).dialog("close");
			}
		},
		open: function () {
			$("#add-categoria-form").find("textarea").css('resize', 'none');
			$("#add-categoria-form").find("textarea").css('overflow', 'hidden');
			$("#add-categoria-form").find("textarea").keyup(function(e) {
				while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
					$(this).height($(this).height()+1);
				};
			});
			$("#add-categoria-form").find("textarea").hover(function(e) {
				while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
					$(this).height($(this).height()+1);
				};
			});
		},
		close: function () {
			$("#add-categoria-form").find("textarea").height('auto');
		}
	});
	
	$('.add-preg-abierta').button().click(function (event) {
		event.preventDefault ? event.preventDefault : event.returnValue = false;
		
		tipoElemento = $(this).attr('tipo');
		padre_id = $(this).attr('padre_id');
		padre = $(this).attr('padre');
		
		$(".add-preg-abierta-padre").html('La pregunta ser&aacute; agregada a la ' + tipoElemento + ':<br />"' + padre.toUpperCase() + '"');
		$("#add-preg-abierta-form").find(".padre-id").attr("value", padre_id);
		$("#add-preg-abierta-form").find(".padre").attr("value", tipoElemento);
		
		$("#add-preg-abierta-form").find("#pregunta").attr("value", "");
		$("#add-preg-abierta-form").find("#descripcion").attr("value", "");
		$("#add-preg-abierta-form").find("#es_obligatoria_abierta").attr("checked", false);
		
		$("#add-preg-abierta-dialog").dialog("open");
	});
	
	$('.edit-preg-abierta').click(function (event) {
		event.preventDefault ? event.preventDefault : event.returnValue = false;
		
		data = $(this).attr('id').split('&');
		preg_id = data[0].split('=');
		tipoElemento = data[1].split('=');
		padre_id = data[2].split('=');
		
		$.ajax({
			url: 'get-pregunta?id=' + preg_id[1],
			success: function (data) {
				$(".add-preg-abierta-padre").html('');
				$("#add-preg-abierta-form").find(".padre-id").attr("value", padre_id[1]);
				$("#add-preg-abierta-form").find(".padre").attr("value", tipoElemento[1]);
				$("#add-preg-abierta-form").find(".pregunta_id").attr("value", preg_id[1]);
				
				$("#add-preg-abierta-form").find("#pregunta").attr("value", data["pregunta"]);
				$("#add-preg-abierta-form").find("#descripcion").attr("value", data["descripcion"]);
				$("#add-preg-abierta-form").find("#es_obligatoria_abierta").attr("checked", (data["es_obligatoria"] == "S" ? 'checked' : false));
				
				$("#add-preg-abierta-dialog").dialog("open");
			}
		});
	});
	
	$("#add-preg-abierta-dialog").dialog({
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		modal: true,
		buttons: {
			"Guardar Pregunta": function() {
				var pregunta = $("#add-preg-abierta-form").find("#pregunta").attr("value");
				if (pregunta != "" && pregunta != null) {
					$("#add-preg-abierta-form").submit();
				} else {
					alert("El campo 'Pregunta' es obligatorio!");
				}
			},
			Cancelar: function() {
				$(this).dialog("close");
			}
		},
		open: function () {
			$("#add-preg-abierta-form").find("textarea").css('resize', 'none');
			$("#add-preg-abierta-form").find("textarea").css('overflow', 'hidden');
			$("#add-preg-abierta-form").find("textarea").keyup(function(e) {
				while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
					$(this).height($(this).height()+1);
				};
			});
			$("#add-preg-abierta-form").find("textarea").hover(function(e) {
				while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
					$(this).height($(this).height()+1);
				};
			});
		},
		close: function () {
			$("#add-preg-abierta-form").find("textarea").height('auto');
		}
	});
	
	$('.add-preg-escala').button().click(function (event) {
		event.preventDefault ? event.preventDefault : event.returnValue = false;
		
		tipoElemento = $(this).attr('tipo');
		padre_id = $(this).attr('padre_id');
		padre = $(this).attr('padre');
		
		$(".add-preg-escala-padre").html('La pregunta ser&aacute; agregada a la ' + tipoElemento + ':<br />"' + padre.toUpperCase() + '"');
		$("#add-preg-escala-form").find(".padre-id").attr("value", padre_id);
		$("#add-preg-escala-form").find(".padre").attr("value", tipoElemento);
		
		$("#add-preg-escala-form").find("#pregunta").attr("value", "");
		$("#add-preg-escala-form").find("#descripcion").attr("value", "");
		$("#add-preg-escala-form").find("#es_obligatoria_escala").attr("checked", false);
		
		$("#add-preg-escala-dialog").dialog("open");
	});
	
	$('.edit-preg-escala').click(function (event) {
		event.preventDefault ? event.preventDefault : event.returnValue = false;
		
		data = $(this).attr('id').split('&');
		preg_id = data[0].split('=');
		tipoElemento = data[1].split('=');
		padre_id = data[2].split('=');
		
		$.ajax({
			url: 'get-pregunta?id=' + preg_id[1],
			success: function (data) {
				$(".add-preg-escala-padre").html('');
				$("#add-preg-escala-form").find(".padre-id").attr("value", padre_id[1]);
				$("#add-preg-escala-form").find(".padre").attr("value", tipoElemento[1]);
				$("#add-preg-escala-form").find(".pregunta_id").attr("value", preg_id[1]);
				
				$("#add-preg-escala-form").find("#pregunta").attr("value", data["pregunta"]);
				$("#add-preg-escala-form").find("#descripcion").attr("value", data["descripcion"]);
				$("#add-preg-escala-form").find("#es_obligatoria_escala").attr("checked", (data["es_obligatoria"] == "S" ? 'checked' : false));
				
				$("#add-preg-escala-dialog").dialog("open");
			}
		});
	});
	
	$("#add-preg-escala-dialog").dialog({
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		modal: true,
		buttons: {
			"Guardar Pregunta": function() {
				var pregunta = $("#add-preg-escala-form").find("#pregunta").attr("value");
				if (pregunta != "" && pregunta != null) {
					$("#add-preg-escala-form").submit();
				} else {
					alert("El campo 'Pregunta' es obligatorio!");
				}
			},
			Cancelar: function() {
				$(this).dialog("close");
			}
		},
		open: function () {
			$("#add-preg-escala-form").find("textarea").css('resize', 'none');
			$("#add-preg-escala-form").find("textarea").css('overflow', 'hidden');
			$("#add-preg-escala-form").find("textarea").keyup(function(e) {
				while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
					$(this).height($(this).height()+1);
				};
			});
			$("#add-preg-escala-form").find("textarea").hover(function(e) {
				while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
					$(this).height($(this).height()+1);
				};
			});
		},
		close: function () {
			$("#add-preg-escala-form").find("textarea").height('auto');
		}
	});
	
	$('.add-preg-sm').button().click(function (event) {
		event.preventDefault ? event.preventDefault : event.returnValue = false;
		
		tipoElemento = $(this).attr('tipo');
		padre_id = $(this).attr('padre_id');
		padre = $(this).attr('padre');
		
		$(".add-preg-sm-padre").html('La pregunta ser&aacute; agregada a la ' + tipoElemento + ':<br />"' + padre.toUpperCase() + '"');
		$("#add-preg-sm-form").find(".padre-id").attr("value", padre_id);
		$("#add-preg-sm-form").find(".padre").attr("value", tipoElemento);
		
		$("#add-preg-sm-form").find("#pregunta").attr("value", "");
		$("#add-preg-sm-form").find("#descripcion").attr("value", "");
		$("#add-preg-sm-form").find("#es_obligatoria_sm").attr("checked", false);
		$("#add-preg-sm-form").find("#opcion_multiple").attr("checked", false);
		$("#add-preg-sm-form").find("#opciones").html("");
		
		$("#add-preg-sm-dialog").dialog("open");
	});
	
	$('.edit-preg-sm').click(function (event) {
		event.preventDefault ? event.preventDefault : event.returnValue = false;
		
		data = $(this).attr('id').split('&');
		preg_id = data[0].split('=');
		tipoElemento = data[1].split('=');
		padre_id = data[2].split('=');
		
		$.ajax({
			url: 'get-pregunta?id=' + preg_id[1],
			success: function (data) {
				console.log(data);
				$(".add-preg-sm-padre").html('');
				$("#add-preg-sm-form").find(".padre-id").attr("value", padre_id[1]);
				$("#add-preg-sm-form").find(".padre").attr("value", tipoElemento[1]);
				$("#add-preg-sm-form").find(".pregunta_id").attr("value", preg_id[1]);
				
				$("#add-preg-sm-form").find("#pregunta").attr("value", data["pregunta"]);
				$("#add-preg-sm-form").find("#descripcion").attr("value", data["descripcion"]);
				$("#add-preg-sm-form").find("#es_obligatoria_sm").attr("checked", (data["es_obligatoria"] == "S" ? 'checked' : false));
				$("#add-preg-sm-form").find("#opcion_multiple").attr("checked", (data["opcion_multiple"] == "S" ? 'checked' : false));
				
				// ARREGLAR TODAVIA
				$("#add-preg-sm-form").find("#opciones").html("");
				opciones = 0;
				for (var i=0; i<data["PreguntaSm"].length; i++) {
					var newInputField = 
						'<div class="preg_opcion">' +
							'<input type="hidden" name="opcion_valor[' + opciones + '][pregunta_sm_id]" id="preg_sm_id" value="' + data["PreguntaSm"][i]["pregunta_sm_id"] + '" />' +
							'<label class="inline" for="preg_sm_id">OPCION <span class="required-field"> * </span></label>' +
							'<input class="opcion text ui-corner-all" type="text" name="opcion_valor[' + opciones + '][nombre]" id="preg_sm_id" tabindex="3" required="required" value="' + data["PreguntaSm"][i]["nombre"] +'" />' +
						'</div>';
					
					/*inputField = '<input type="text" id="opcion" name="opcion-' + (i+1) +'" class="text ui-corner-all" value="' + data["PreguntaSm"][i]["nombre"] + '" />';
					inputField += ' <a href="#" class="del-opcion" id="del-opcion-' + (i+1) + '">Del</a><br />';*/
					$("#opciones").append(newInputField);
					opciones++;
				}
				
				$("#add-preg-sm-dialog").dialog("open");
			}
		});
	});
	
	$("#add-preg-sm-dialog").dialog({
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		modal: true,
		buttons: {
			"Guardar Pregunta": function() {
				var pregunta = $("#add-preg-sm-form").find("#pregunta").attr("value");
				if (pregunta != "" && pregunta != null) {
					$("#add-preg-sm-form").submit();
				} else {
					alert("El campo 'Pregunta' es obligatorio!");
				}
			},
			Cancelar: function() {
				$(this).dialog("close");
			}
		},
		open: function () {
			$("#add-preg-sm-form").find("textarea").css('resize', 'none');
			$("#add-preg-sm-form").find("textarea").css('overflow', 'hidden');
			$("#add-preg-sm-form").find("textarea").keyup(function(e) {
				while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
					$(this).height($(this).height()+1);
				};
			});
			$("#add-preg-sm-form").find("textarea").hover(function(e) {
				while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
					$(this).height($(this).height()+1);
				};
			});
		},
		close: function () {
			$("#add-preg-sm-form").find("textarea").height('auto');
		}
	});
	
	$('.agregable').hover(function () {
		$(this).find('.botonera').css('display', 'inline');
	}, function () {
		$(this).find('.botonera').css('display', 'none');
	});
	
	$('#agregar-opcion').click(function(e) {
		e.preventDefault();
		var newInputField = 
			'<div class="preg_opcion">' +
				'<input type="hidden" name="opcion_valor[' + opciones + '][pregunta_sm_id]" id="preg_sm_id" value="new" />' +
				'<label class="inline" for="preg_sm_id">OPCION <span class="required-field"> * </span></label>' +
				'<input class="opcion text ui-corner-all" type="text" name="opcion_valor[' + opciones + '][nombre]" id="preg_sm_id" tabindex="3" required="required" />' +
			'</div>';
		
		$('#opciones').fadeIn().append(newInputField);
		opciones++;
	});
	
	$('#borrar-opcion').click(function () {
		if (opciones > 0) {
			$('.preg_opcion:last').remove();
			opciones--;
		}
	});
	
	$('#borrar-todo').click(function () {
		while (opciones > 0) {
			$('.preg_opcion:last').remove();
			opciones--;
		}
	});
	
	/*$('#agregar-opcion').click(function(e) {
		e.preventDefault();
		var nroOpciones = $(":input[class='opcion']").length + 1;
		var inputField = '<input type="text" name="opcion-' + nroOpciones +'" class="opcion text ui-corner-all"  />';
		inputField += ' <a class="del-opcion" id="del-opcion-' + nroOpciones + '">-</a><br />';
		$("#opciones").append(inputField);
	});*/
});