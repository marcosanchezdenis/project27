$(document).ready(function () {
	$("#close").click(function (e) {
		e.preventDefault();
		window.open('', '_self', '');
		window.close();
	});
});