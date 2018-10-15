$(document).ready(function () {
	var cantidad = $('select').size();
	
	$('#agregar-rol').click(function (event) {
		$.ajax({
			url: 'roles',
			success: function (roles) {
				opciones = "";
				for (var i=0; i<roles.length; i++) {
					opciones += '<option value=' + roles[i]["rol_id"] + ">" + roles[i]["nombre"] + "</option>";
				}
				
				var newRolField = 
					'<div class="usuario_perm">' +
						'<label for="usuario_perm_id">ROL <span class="required-field"> * </span></label>' +
						'<select id="usuario_perm_id" name="roles[]" tabindex="7">' +
							'<option selected="selected" value="none">-- Seleccione un rol --</option>' +
							opciones +
						'</select>' +
					'</div>';
				
				$('#nuevos-roles').fadeIn().append(newRolField);
				cantidad++;
			}
		});
	});
	
	$('#borrar-rol').click(function () {
		if (cantidad > 0) {
			$('.usuario_perm:last').remove();
			cantidad--;
		}
	});
	
	$('#borrar-todo').click(function () {
		while (cantidad > 0) {
			$('.usuario_perm:last').remove();
			cantidad--;
		}
	});
	
	var form = $("#crear-usuario");
	
	var nombre = $("#nombre");
	var nombreInfo = $("#nombreInfo");
	var apellido = $("#apellido");
	var apellidoInfo = $("#apellidoInfo");
	var email = $("#email");
	var emailInfo = $("#emailInfo");
	
	var usuario = $("#usuario");
	var usuarioInfo = $('#usuarioInfo');
	var contrasena = $("#contrasena");
	var contrasenaInfo = $('#contrasenaInfo');
	var confirmacion = $("#confirmacion");
	var confirmacionInfo = $("#confirmacionInfo");
	
	// On blur
	nombre.blur(validateNombre);
	apellido.blur(validateApellido);
	email.blur(validateEmail);
	usuario.blur(validateUsuario);
	contrasena.blur(validateContrasena);
	confirmacion.blur(validateConfirmacion);
	
	// On keyup
	contrasena.keyup(validateContrasena);
	confirmacion.keyup(validateConfirmacion);
	
	form.submit(function () {
		if (validateNombre() & validateApellido() & validateEmail() &
			validateUsuario() & validateContrasena() & validateConfirmacion())
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
	
	function validateUsuario() {
		if (usuario.val().length <= 0) {
			usuarioInfo.html(getNotValidImg());
			usuario.css('background-color', '#FFD2D2');
			return false;
		} else {
			$.ajax({
				url: 'usuarios',
				success: function (usuarios) {
					var found = false;
					for (var i=0; i<usuarios.length; i++) {
						if (usuario.val() == usuarios[i]['usuario'] &
							$("#usuario_id").val() != usuarios[i]['usuario_id']) {
							found = true;
							break;
						}
					}
					
					if (found) {
						usuarioInfo.html(getNotValidImg());
						usuario.css('background-color', '#FFD2D2');
						return false;
					}
					
					usuarioInfo.html(getValidImg());
					usuario.css('background-color', 'white');
					return true;
				}
			});
		}
		return true;
	}
	
	function validateContrasena() {
		if ($("#contrasena").length > 0) {
			if (contrasena.val().length <= 0) {
				contrasenaInfo.html(getNotValidImg());
				contrasena.css('background-color', '#FFD2D2');
				return false;
			}
			contrasenaInfo.html(getValidImg());
			contrasena.css('background-color', 'white');
			validateConfirmacion();
			return true;
		}
		return true;
	}
	
	function validateConfirmacion() {
		if ($("#confirmacion").length > 0) {
			var pass1 = $("#contrasena");
			var pass2 = $("#confirmacion");
			
			if (pass1.val() != "") { 
				if (pass1.val() != pass2.val()) {
					confirmacionInfo.html(getNotValidImg());
					confirmacion.css('background-color', '#FFD2D2');
					return false;
				} else {
					confirmacionInfo.html(getValidImg());
				}
			}
			
			confirmacion.css('background-color', 'white');
			return true;
		}
		return true;
	}
	
	function getValidImg() {
		return ' <img src="/encuestas/images/tick.png" alt="Bien" />';
	}
	
	function getNotValidImg() {
		return ' <img src="/encuestas/images/cross.png" alt="Mal" />';
	}
});