@extends('layouts.app')
@section('header_script')
<link href="{{ asset('assets/DataTables/css/jquery.dataTables.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/DataTables/css/responsive.dataTables.min.css') }}" rel="stylesheet" />
@endsection
@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Product List') }}
</h2>
@endsection
@section('content') 
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{ __('Products') }}                    
                </div>
            </div>
            
            <!--Card-->
            <div id='recipients' class="col-span-3 p-5 mt-6 lg:mt-0 rounded shadow bg-white"> 

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="mt-4">
                  <h3>Category List</h3>                
                  <select class="block mt-1 w-full" id="category" name="category">
                      <option value="0"> Select category </option>
                      @foreach($categories as $category)
                        <option value="{{app_encrypt($category->id)}}">{{ $category->name }}</option>
                      @endforeach                            
                  </select>
                </div>             
              </div>
              <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-6">
              <table id="dt_products" data-id="dt_products" class="stripe hover" style="width:100%;padding-top:1em;padding-bottom:1em;" data-callback="reint_dt_products_js">
                <thead>
                <tr>
                  <th data-priority="1">S.no</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Category</th>
                  <th>Action</th>
                </tr>
                </thead>
              </table>       
            </div>
            <!--/Card-->
          </div>
        </div>
    </div>
@endsection
@section('footer_script')
<!-- jQuery dataTable core JS Library -->
<script src="{{ asset('assets/DataTables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/DataTables/js/dataTables.responsive.min.js') }}"></script>
<!-- initialize Js-->
<script src="{{ asset('assets/js/datatable_int.js') }}"></script>

<script src="{{ asset('assets/js/ajax_common.js') }}"></script>
<script>    
var setOptions = {
    zeroRecord:'No Record',
    data:{}
};

initDataTable('dt_products',"{{ route('dt.products') }}",setOptions);

function success_reint_dt_products_js(){
}
function reinit_dt(){  
  $('#dt_products').dataTable().fnClearTable();
}
$('#category').on('change', function() { 
  setOptions.data.category = $(this).val(); 
  //console.log(setOptions);
  $('#dt_products').dataTable().fnDestroy();
  initDataTable('dt_products',"{{ route('dt.products') }}",setOptions);
});

</script>
@endsection
