$(document).ready(function () {
	var cantidad = $('.escala_valor').size();
	
	$('#agregar-valor').click(function (event) {
		valoresInfo.css('display', 'none');
		var newValorField = 
			'<div class="escala_valor">' +
				'<input type="hidden" name="escala_valor[' + cantidad + '][escala_valor_id]" id="escala_valor_id" value="new" />' +
				'<label for="escala_valor_id">VALOR <span class="required-field"> * </span></label>' +
				'<input class="valor" type="text" name="escala_valor[' + cantidad + '][valor]" id="escala_valor_id" tabindex="3" required="required" />' +
				'<label for="escala_valor_id">DESCRIPCION <span class="required-field"> * </span></label>' +
				'<input class="descrip" type="text" name="escala_valor[' + cantidad + '][descrip]" id="escala_valor_id" tabindex="3" required="required" />' +
			'</div>';
		
		$('#nuevos-valores').fadeIn().append(newValorField);
		cantidad++;
	});
	
	$('#borrar-valor').click(function () {
		if (cantidad > 0) {
			$('.escala_valor:last').remove();
			cantidad--;
		}
	});
	
	$('#borrar-todo').click(function () {
		while (cantidad > 0) {
			$('.escala_valor:last').remove();
			cantidad--;
		}
	});
	
	var form = $("#crear-escala");
	
	var nombre = $("#nombre");
	var nombreInfo = $("#nombreInfo");
	var valor = $(".valor");
	var valoresInfo = $("#valoresInfo");
	
	// On blur
	nombre.blur(validateNombre);
	
	form.submit(function () {
		if (validateNombre() && validateCantidad() && validateValores())
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
	
	function validateCantidad()
	{
		if (cantidad <= 0) {
			valoresInfo.html('Debe crear valores para la escala');
			valoresInfo.css('display', 'block');
			return false;
		}
		
		valoresInfo.css('display', 'none');
		return true;
	}
	
	function validateValores()
	{
		$('.valor').each(function () {
			if ($(this).val().length <= 0) {
				valorInfo.html(getNotValidImg());
				valor.css('background-color', '#FFD2D2');
				return false;
			}
		});
		
		valorInfo.html(getValidImg());
		valor.css('background-color', 'white');
		return true;
	}
	
	function getValidImg() {
		return ' <img src="/encuestas/images/tick.png" alt="Bien" />';
	}
	
	function getNotValidImg() {
		return ' <img src="/encuestas/images/cross.png" alt="Mal" />';
	}
});