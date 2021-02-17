/* some common functions */
function jsRedirect(re_url, time){
	if(typeof re_url == 'undefined')
		re_url = BASE_URL;
	
	if(typeof time == 'undefined')
		time = 300;

	setTimeout(function(){ window.location.href = re_url; }, time);
}

/* toastr option init */
toastr.options.onHidden = function() {toastr.clear();}
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-center",
  "preventDuplicates": false,
  //"onclick": null,
  //"showDuration": 300,
  //"hideDuration": 1000,
  "fadeOut": 1000,
  "timeOut": 3000,
  //"extendedTimeOut": 1000,
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut",
  "maxOpened": 1,
};

var ajaxLoader = $(".ajax_loader_icon_outer");
var pageLoader = $('.full_page_loader');