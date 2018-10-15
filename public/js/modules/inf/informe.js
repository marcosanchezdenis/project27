$(document).ready(function () {
	
	$("#indice-introduccion").click(function () {
		$(".oculto").css('display', 'none');
		$(".up").css('display', 'block');
		$("#introduccion").fadeIn();
	});
	
	$("#indice-glosario").click(function () {
		$(".oculto").css('display', 'none');
		$(".up").css('display', 'block');
		$("#glosario").fadeIn();
	});
	
	$("#indice-perfil").click(function () {
		$(".oculto").css('display', 'none');
		$(".up").css('display', 'block');
		$("#perfil").fadeIn();
	});
	
	$("#indice-media-dpto").click(function () {
		$(".oculto").css('display', 'none');
		$(".up").css('display', 'block');
		$("#media-dpto").fadeIn();
	});
	
	$("#indice-nota-docente").click(function () {
		$(".oculto").css('display', 'none');
		$(".up").css('display', 'block');
		$("#nota-docente").fadeIn();
	});

	$("#indice-nota-materia").click(function () {
		$(".oculto").css('display', 'none');
		$(".up").css('display', 'block');
		$("#nota-materia").fadeIn();
	});
	
	$("#descargar-informe").click(function() {
		$(".up").css('display', 'none');
		$(".oculto").css('display', 'block');
	});
	
	$(".up").click(function () {
		$('html, body').animate({ scrollTop: 0 }, 'slow');
	});
});