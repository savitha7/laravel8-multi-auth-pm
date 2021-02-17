<script type="text/javascript">
	var new_csrfToken = $('[name="csrf_token"]').attr('content');
	new_csrfToken = '{{ csrf_token() }}';
	setInterval(refreshToken, 3600000); // 1 hour
	   
	function refreshToken(){
		$.get("/refresh-csrf").done(function(data){
			new_csrfToken = data; // the new token
		});            
	}
	
	var PAGE_SET = "<?=isset($page_set)?$page_set:'';?>";
</script>