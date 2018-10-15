$(document).ready(function () {
	var top = $("#cabecera").offset().top - parseFloat($("#cabecera").css('marginTop').replace(/auto/,0));
	
	/* 
 * 	// Comment for mobile version
  	 $(window).scroll(function (event) {
		var y = $(this).scrollTop();
		
		if (y >= top) {
			$('#cabecera').addClass('fixed');
		} else {
			$('#cabecera').removeClass('fixed');
		}
	});*/
	
	$(".answer-pregunta-abierta").keyup(function(e) {
		while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
			$(this).height($(this).height()+1);
		};
	});
	
	var form = $("#completar-encuesta");
	
	form.submit(function () {
		console.log('Sel: ' + validateSeleccionObligatorias());
		console.log('Esc: ' + validateEscaladasObligatorias());
		console.log('Abi: ' + validateAbiertasObligatorias());
		
		if (validateSeleccionObligatorias() &
			validateEscaladasObligatorias() &
			validateAbiertasObligatorias())
			return true;
		else
			return false;
	});
	
	function validateAbiertasObligatorias()
	{
		var error = false;
		$(".answer-pregunta-abierta").each(function () {
			if ($(this).attr('obligatoria') == "S") {
				if ($(this).val() == "") {
					$(this).css('background-color', 'red');
					error = true;
					return false;
				}
			}
			$(this).css('background-color', 'inherit');
			return true;
		});
		return !error;
	}
	
	function validateEscaladasObligatorias()
	{
		var error = false;
		$(".answer-pregunta-escalada").each(function () {
			if ($(this).attr('obligatoria') == "S") {
				if ($(this).find(":selected").val() == "none") {
					$(this).css('background-color', 'red');
					error = true;
					return false;
				}
			}
			$(this).css('background-color', 'inherit');
			return true;
		});
		return !error;
	}
	
	function validateSeleccionObligatorias()
	{
		var error = false;
		$(".opciones").each(function () {
			if ($(this).attr('obligatoria') == "S") {
				if ($(this).find(':input:checked').length <= 0) {
					$(this).css('background-color', 'red');
					error = true;
					return false;
				}
			}
			$(this).css('background-color', 'inherit');
			return true;
		});
		return !error;
	}
});
