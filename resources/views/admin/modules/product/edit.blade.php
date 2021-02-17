@extends('admin.layouts.app')
@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Edit the product : ').$name}}
</h2>
@endsection
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{ __('Edit the product') }}
                    <x-app-icon-link :href="route('product.index')" class="float-right">
                       <i class="fas fa-arrow-left fa-fw"></i>
                    </x-app-icon-link>
                </div>
            </div>

            <!--Card-->
             <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">             
                <form method="POST" action="{{ route('product.update',['product'=>request()->route('product')]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-label for="name" :value="__('Name')" />

                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{$name}}" required autofocus />
                    </div>

                    <div class="mt-4">
                        <x-label for="description" :value="__('Description')" />

                        <textarea class="resize border rounded-md block mt-1 w-full" rows="5" name="description" required >{{$description}}</textarea>
                    </div>

                    <div class="mt-4">
                        <x-label for="short_description" :value="__('Short Description')" />

                        <textarea id="editor" class="resize border rounded-md block mt-1 w-full" name="short_description" required >{{$short_description}}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="mt-4">
                    <x-label for="category" :value="__('Category')" />
                        <select class="block mt-1 w-full" name="category">
                            <option value="">Select a category</option>
                            @foreach($categories as $key => $category)
                                @if ($category_id == $category->id)
                                    <option value="{{ $category->id }}" selected> {{ $category->name }} </option>
                                @else
                                    <option value="{{ $category->id }}"> {{ $category->name }} </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mt-4">
                        <x-label for="status" :value="__('Product Status')" />
                        <select class="block mt-1 w-full" name="status">
                            <option value="1" @if($status == 1) selected @endif> Enable </option>
                            <option value="0" @if($status == 0) selected @endif> Disable </option>
                        </select>
                    </div>
                    </div>                    

                    <div class="mt-4">
                        <input type="file" name="images[]" class="form-control" multiple>
                    </div>

                    <div id='recipients' class="p-8 lg:mt-0 rounded shadow bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            @foreach($product->images as $image)
                            <div class="img_div"><i class="fa fa-fw fa-times img_div_i" data-id="{{$image->id}}"></i>
                            <img src="<?php echo asset('storage/products/'.$image->image_name) ?>" class="object-contain md:object-scale-down">
                            </div>
                            @endforeach                   
                        </div>
                        <div id="remove_img_div">
                            
                        </div>
                    </div>                                 

                    <div class="flex items-center justify-end mt-4">
                        <x-button class="ml-4">
                            {{ __('Update the product') }}
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
    $(".img_div_i").click(function(){
        var mid = $(this).data('id');
        $('#remove_img_div').append('<input type="hidden" name="remove_img[]" value="'+mid+'" />');
        $(this).closest('.img_div').remove();
    });
</script>
@endsection