/**
 * MENU DINAMICO
 */
$(document).ready(function() {
	$('ul.menu-bar li').hover(
	function() {
		if ($(this).has('ul').length) {
			$('#sub-menu').css('display', 'block');
		}
	}, function() {
		if ($(this).has('ul').length) {
			$('#sub-menu').css('display', 'none');
		}
	});
});