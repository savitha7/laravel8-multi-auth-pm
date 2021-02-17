@extends('admin.layouts.app')
@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Manage Product') }}
</h2>
@endsection
@section('content') 
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{ __('Products') }}                    
                    <x-app-icon-link :href="route('product.create')" class="float-right"><i class="fas fa-plus fa-fw"></i></x-app-icon-link >
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
                  <th>Status</th>
                  <th>Created at</th>
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
@include('admin.modals.modal')
<script>    
var setOptions = {
    zeroRecord:'No Record',
    data:{}
};

initDataTable('dt_products',"{{ route('admin.dt.products') }}",setOptions);

function success_reint_dt_products_js(){
  initTwModal();
}

function reinit_dt(){  
  $('#dt_products').dataTable().fnClearTable();
  initTwModal();
}
$('#category').on('change', function() { 
  setOptions.data.category = $(this).val(); 
  $('#dt_products').dataTable().fnDestroy();
  initDataTable('dt_products',"{{ route('admin.dt.products') }}",setOptions);
});
//status update function
function updateStatus(dataThis){        
    var eid = $(dataThis).data("id");
    var actionUrl = "{{route('product.status.update')}}";
    var isChecked = $('input[name='+eid+']').prop("checked"); 
    var seializedPostData = "eid="+eid+"&status="+isChecked;
    ajax_request_call('POST',actionUrl,seializedPostData);
}
</script>
@endsection
@section('modals')
@include('admin.modals.delete')
@endsection