$(document).ready(function () {
	$(".delete-button").click(function (event) {
		event.preventDefault;
		$preventionMsg = "Esta seguro que desea borrar el usuario?\nUna vez borrado no podr\u00e1 restaurarlo.";
		if (!confirm($preventionMsg)) {
			return false;
		}
	});
});