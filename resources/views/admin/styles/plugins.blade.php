@if(isset($page_set) && ($page_set == 'categories' || $page_set == 'products'))
	<link href="{{ asset('assets/DataTables/css/jquery.dataTables.min.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/DataTables/css/responsive.dataTables.min.css') }}" rel="stylesheet" />
@endif