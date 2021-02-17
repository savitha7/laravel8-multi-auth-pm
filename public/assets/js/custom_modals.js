/* TW modal script */
var tw_modal = '.tw-modal';
var tw_modal_active = 'tw-modal-active';
var twModalInit = function () {   
    
    var handleTwModal_init = function() { 
	    var tw_modal_open = '.tw-modal-open';	    
	    var tw_modal_overlay = '.tw-modal-overlay';
		var tw_modal_content = '.tw-modal-content';
	    var tw_modal_close = '.tw-modal-close';
              
		
		var openmodal = document.querySelectorAll(tw_modal_open); console.log(openmodal);
		for (var i = 0; i < openmodal.length; i++) {
		  openmodal[i].addEventListener('click', function(event){
			event.preventDefault()
			render_toggle_tw_modal(this);
		  })
		}

		/*const overlay = document.querySelector(tw_modal_overlay+':not('+tw_modal_content+')')
		overlay.addEventListener('click', getTheModal)*/

		var closemodal = document.querySelectorAll(tw_modal_close)
		for (var i = 0; i < closemodal.length; i++) {
		  closemodal[i].addEventListener('click', getTheModal)
		}

		document.onkeydown = function(evt) {
		  evt = evt || window.event
		  var isEscape = false
		  if ("key" in evt) {
			isEscape = (evt.key === "Escape" || evt.key === "Esc")
		  } else {
			isEscape = (evt.keyCode === 27)
		  }
		  if (isEscape && document.body.classList.contains(tw_modal_active)) {
		  	get_opened_tw_modal_reset();
		  }
		};
    }
    return {
        /* main function to initiate the module */
        init: function () {	
			handleTwModal_init();	
        }

    };
}();

function initTwModal(){ 
	/* call submitHandler */
	twModalInit.init();
}
/* the function used as callback for close events*/
function getTheModal(){
	var opened_modal = $(this).parent().closest(tw_modal);
	reset_toggle_tw_modal(opened_modal);	
}

function toggleModal (opened_modal) {
	var from = from != null?from:null;
	const body = document.querySelector('body')
	$(opened_modal).toggleClass('opacity-0')
	$(opened_modal).toggleClass('pointer-events-none')
	body.classList.toggle(tw_modal_active)
}

/* modal form render functions */
function render_toggle_tw_modal(tw_modal_btn){console.log(tw_modal_btn);
	var get_modal = $($(tw_modal_btn).data('target'));
	var action = $(tw_modal_btn).data('action');
	var msg = $(tw_modal_btn).data('msg');
	$(get_modal).find('form').attr('action', action);
	$(get_modal).find('.tw-modal-content .tw_modal_note').html(msg);
	toggleModal(get_modal,'open');
}

/* modal form reset functions */
function reset_toggle_tw_modal(opened_modal){
	$(opened_modal).find('.tw-modal-content .tw_modal_note').html('');
	$(opened_modal).find('form').attr('action', '');
	toggleModal(opened_modal);
}

function get_opened_tw_modal_reset(){
	/* get the open tw-modal */
	var opened_modal = $(tw_modal+":not(.opacity-0,.pointer-events-none)");
	$(opened_modal).find('.tw-modal-content .tw_modal_note').html('');
	$(opened_modal).find('form').attr('action', '');
	toggleModal(opened_modal);
}
/* ajax callback functions */
function success_tw_modal_delete(){
	get_opened_tw_modal_reset();
	reinit_dt();
}
