<script>
  @if(Session::has('message'))
    var type = "{{ Session::get('alert-type', 'info') }}";
    switch(type){
        case 'info':
            toastr.info("{{ Session::get('message') }}");
            break;
        
        case 'warning':
            toastr.warning("{{ Session::get('message') }}");
            break;

        case 'success':
            toastr.success("{{ Session::get('message') }}");
            break;

        case 'error':
            toastr.error("{{ Session::get('message') }}");
            break;
    }
  @endif
</script>
@if(isset($page_set) && ($page_set == 'categories' || $page_set == 'products'))
	<!-- jQuery dataTable core JS Library -->
	<script src="{{ asset('assets/DataTables/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('assets/DataTables/js/dataTables.responsive.min.js') }}"></script>
	<!-- initialize Js-->
	<script src="{{ asset('assets/js/datatable_int.js') }}"></script>

	<script src="{{ asset('assets/js/ajax_common.js') }}"></script>
@endif