$(document).ready(function() {
	// get current URL path and assign 'active' class
    var url = window.location.href.replace(window.location.protocol, "");
	$('.navbar-nav > li > a[href="'+url+'"]').parent().addClass('active');
})