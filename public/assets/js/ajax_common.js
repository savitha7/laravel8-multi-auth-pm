/* some common functions */
var mtd_prefix = 'success_';
var false_prefix = 'failed_';
var is_loader = false;

/*
* request for ajax call
* input : request url, post data etc
* */
function ajax_request_call(postMtd='POST', request_url, postData, ajax_msg,callback_function,false_callback){
	$.ajax({ 
		type: postMtd,
		url: request_url,
		data: postData,
		processData: false,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},		
		success: function(resdata){		
			if(resdata.status === true ){
				renderMessage(resdata.status,resdata.message,ajax_msg);
				
				if(typeof callback_function !== 'undefined' && callback_function !== ''){
					window[mtd_prefix+callback_function](resdata); 
				} else {					
					if(typeof resdata.redirect_url !== 'undefined' && resdata.redirect_url !== ''){
						jsRedirect(resdata.redirect_url, 1000);	
					}
				}			
			} else if(resdata.status === false){ 
				if(typeof false_callback !== 'undefined' && false_callback !== ''){
					window[false_prefix+false_callback](resdata); 
				}

				renderMessage(resdata.status,resdata.message,ajax_msg);
				renderErrors(resdata.errors,ajax_msg);								
			} else {
				if(typeof false_callback !== 'undefined' && false_callback !== ''){
					window[false_prefix+false_callback](resdata); 
				}
				
				renderMessage(resdata.status,resdata.message,ajax_msg);				
			}
		}
	});
}

function renderMessage(status,message,ajax_msg){		
	if(typeof message !== 'undefined' && message !== ''){
		if(status === true ){		
			if(typeof ajax_msg !== 'undefined' && ajax_msg !== ''){
				$('#'+ajax_msg).html("<span class='res-success'>"+message+"</span>");
			} else {
				toastr.clear();
				toastr.success(message);
			}
		} else {
			if(typeof ajax_msg !== 'undefined' && ajax_msg !== ''){
				$('#'+ajax_msg).html(message);
			} else {
				toastr.clear();
				toastr.error(message);
			}
		}						
	}
}

function renderErrors(errors,ajax_msg){
	if(typeof errors !== 'undefined'){		
		if(Array.isArray(errors)){
			var message ='';
			$.each( errors, function( key, value ) {
				message += "<p>"+value+"</p>";
			});					
			if(typeof ajax_msg !== 'undefined' && ajax_msg !== ''){
				$('#'+ajax_msg).html(message);	
			} else {
				toastr.clear();
				toastr.error(message);
			}							
		} else {					
			if(typeof ajax_msg !== 'undefined' && ajax_msg !== ''){
				$('#'+ajax_msg).html(errors);
			} else {
				toastr.clear();
				toastr.error(errors);
			}
		}							
	}
}