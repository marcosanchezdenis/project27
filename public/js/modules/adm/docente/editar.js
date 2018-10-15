$(document).ready(function () {
var cantidad = $('select').size();
	
	$('#agregar-dpto').click(function (event) {
		$.ajax({
			url: 'departamentos',
			success: function (departamentos) {
				opciones = "";
				for (var i=0; i<departamentos.length; i++) {
					opciones += '<option value=' + departamentos[i]["departamento_id"] + ">" + departamentos[i]["nombre"] + "</option>";
				}
				
				var newRolField = 
					'<div class="docente_dpto">' +
						'<label for="docente_dpto_id">DEPARTAMENTO <span class="required-field"> * </span></label>' +
						'<select id="docente_dpto_id" name="departamentos[]" tabindex="7">' +
							'<option selected="selected" value="none">-- Seleccione un departamento --</option>' +
							opciones +
						'</select>' +
					'</div>';
				
				$('#nuevos-dptos').fadeIn().append(newRolField);
				cantidad++;
			}
		});
	});
	
	$('#borrar-dpto').click(function () {
		if (cantidad > 0) {
			$('.docente_dpto:last').remove();
			cantidad--;
		}
	});
	
	$('#borrar-todo').click(function () {
		while (cantidad > 0) {
			$('.docente_dpto:last').remove();
			cantidad--;
		}
	});
	
	var form = $("#editar-docente");
	
	var nombre = $("#nombre");
	var nombreInfo = $("#nombreInfo");
	var apellido = $("#apellido");
	var apellidoInfo = $("#apellidoInfo");
	var email = $("#email");
	var emailInfo = $("#emailInfo");
	
	// On blur
	nombre.blur(validateNombre);
	apellido.blur(validateApellido);
	email.blur(validateEmail);
	
	form.submit(function () {
		if (validateNombre() & validateApellido() & validateEmail())
			return true;
		else
			return false;
	});
	
	function validateNombre() {
		if (nombre.val().length <= 0) {
			nombreInfo.html(getNotValidImg());
			nombre.css('background-color', '#FFD2D2');
			return false;
		}
		nombreInfo.html(getValidImg());
		nombre.css('background-color', 'white');
		return true;
	}
	
	function validateApellido() {
		if (apellido.val().length <= 0) {
			apellidoInfo.html(getNotValidImg());
			apellido.css('background-color', '#FFD2D2');
			return false;
		}
		apellidoInfo.html(getValidImg());
		apellido.css('background-color', 'white');
		return true;
	}
	
	function validateEmail() {
		var a = $("#email").val();
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
		
		if (a != "") {
			if (!filter.test(a)) {
				emailInfo.html(getNotValidImg());
				email.css('background-color', '#FFD2D2');
				return false;
			} else {
				emailInfo.html(getValidImg());
			}
		} else {
			emailInfo.html("");
		}
		
		email.css('background-color', 'white');
		return true;
	}
	
	function getValidImg() {
		return ' <img src="/encuestas/images/tick.png" alt="Bien" />';
	}
	
	function getNotValidImg() {
		return ' <img src="/encuestas/images/cross.png" alt="Mal" />';
	}
});