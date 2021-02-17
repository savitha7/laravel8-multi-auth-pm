@extends('layouts.app')
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Product view') }}
    </h2>
@endsection
@section('content') 
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{ __('Product:') }}  {{$name}}                 
                    <x-app-icon-link :href="route('user.product.index')" class="float-right"><i class="fas fa-arrow-left fa-fw"></i></x-app-icon-link >
                </div>
            </div>

            <!--Card-->            
            <div id='recipients' class="p-8 lg:mt-0 rounded shadow bg-white">
            <div class="mt-4"><span class="font-bold">{{ __('Name:') }}</span>  {{$name}}  </div>
            <div class="mt-4"><span class="font-bold">{{ __('Category:') }}</span>  {{$category_name}}  </div>
            <div class="mt-4"><p class="font-bold">{{ __('Description:') }}</p> {{$description}}  </div>
            <div class="mt-4"><p class="font-bold">{{ __('Short Description:') }} </p>{!!$short_description!!}  </div>
            </div>

            <div id='recipients' class="p-8 lg:mt-0 rounded shadow bg-white">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @foreach($product->images as $image)
                    <div>
                    <img src="<?php echo asset('storage/products/'.$image->image_name) ?>" class="object-contain md:object-scale-down">
                    </div>
                    @endforeach                   
                </div>
            </div>
        </div>
    </div>
@endsection
