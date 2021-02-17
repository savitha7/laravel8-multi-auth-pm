@extends('admin.layouts.app')
@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Manage Category') }}
</h2>
@endsection
@section('content') 
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{ __('Categories') }}                    
                    <x-app-icon-link :href="route('category.create')" class="float-right"><i class="fas fa-plus fa-fw"></i></x-app-icon-link >
                </div>
            </div>

            <div class="grid grid-cols-1">

            <!--Card-->
            <div id='recipients' class="p-5 mt-6 lg:mt-0 rounded shadow bg-white">   
                          
                <table id="dt_categories" data-id="dt_categories" class="stripe hover" style="width:100%;padding-top:1em;padding-bottom:1em;" data-callback="reint_dt_categories_js">
                <thead>
                <tr>
                  <th data-priority="1">S.no</th>
                  <th>Name</th>
                  <th>Description</th>
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

initDataTable('dt_categories',"{{ route('admin.dt.categories') }}",setOptions);

function success_reint_dt_categories_js(){
  initTwModal();
}

function reinit_dt(){  
  $('#dt_categories').dataTable().fnClearTable();
  initTwModal();
}

//status update function
function updateStatus(dataThis){        
    var eid = $(dataThis).data("id");
    var actionUrl = "{{route('category.status.update')}}";
    var isChecked = $('input[name='+eid+']').prop("checked"); 
    var seializedPostData = "eid="+eid+"&status="+isChecked;
    ajax_request_call('POST',actionUrl,seializedPostData);
}
</script>
@endsection
@section('modals')
@include('admin.modals.delete')
@endsection