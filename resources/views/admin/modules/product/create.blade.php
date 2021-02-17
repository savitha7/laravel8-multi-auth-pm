@extends('admin.layouts.app')
@section('header') 
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Add a new product') }}
</h2>
@endsection
@section('content') 
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{ __('Add a new product') }}
                    <x-app-icon-link :href="route('product.index')" class="float-right">
                       <i class="fas fa-arrow-left fa-fw"></i>
                    </x-app-icon-link>
                </div>
            </div>

            <!--Card-->
             <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">             
                <form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <x-label for="name" :value="__('Name')" />

                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                    </div>

                    <div class="mt-4">
                        <x-label for="description" :value="__('Description')" />

                        <textarea class="resize border rounded-md block mt-1 w-full" name="description" rows="5" required >{{old('description')}}</textarea>
                    </div>

                    <div class="mt-4">
                        <x-label for="short_description" :value="__('Short Description')" />

                        <textarea id="editor" class="resize border rounded-md block mt-1 w-full" name="short_description" required >{{old('short_description')}}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="mt-4">
                    <x-label for="category" :value="__('Category')" />
                        <select class="block mt-1 w-full" name="category">
                            @foreach($categories as $key => $category)
                                <option value="{{ $category->id }}"> {{ $category->name }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <x-label for="status" :value="__('Product Status')" />
                        <select class="block mt-1 w-full" name="status">
                            <option value="1"> Enable </option>
                            <option value="0"> Disable </option>                             
                        </select>
                    </div>                   

                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mt-4">
                        <input type="file" name="images[]" class="form-control" multiple>
                    </div>
                    
                    <div class="mt-4">
                    @if ($message = Session::get('success'))
                    <img src="images/{{ Session::get('featured_image') }}">
                    @endif
                    </div>

                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-button class="ml-4">
                            {{ __('Add A new product') }}
                        </x-button>
                    </div>
                </form>               
            </div>
            <!--/Card-->
        </div>
    </div>
@endsection
@section('footer_script')
<script src="https://cdn.ckeditor.com/4.15.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'editor' );    
</script>
@endsection