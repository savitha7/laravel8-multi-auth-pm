 <script src="{{ asset('assets/js/custom_modals.js') }}" ></script>
 <script>	 
	 /**
	 * delete modal script
	 */	
	$("#delete-tw-modal-form").submit(function(e){
	    e.preventDefault();
	    //get the action-url of the form
	    var actionurl = e.currentTarget.action;
	    ajax_request_call('POST',actionurl,$("#delete-tw-modal-form").serialize(),null,'tw_modal_delete');
	});
</script>