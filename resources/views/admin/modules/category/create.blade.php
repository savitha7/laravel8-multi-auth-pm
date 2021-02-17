@extends('admin.layouts.app')
@section('header') 
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Category') }}
    </h2>
@endsection
@section('content') 
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{ __('Add new category') }}
                    <x-app-icon-link :href="route('category.index')" class="float-right">
                       <i class="fas fa-arrow-left fa-fw"></i>
                    </x-app-icon-link>
                </div>
            </div>

            <!--Card-->
             <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">             
                <form method="POST" action="{{ route('category.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <x-label for="name" :value="__('Name')" />

                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                    </div>

                    <div class="mt-4">
                        <x-label for="description" :value="__('Description')" />

                        <textarea class="resize border rounded-md block mt-1 w-full" name="description" required >{{old('description')}}</textarea>
                    </div>

                    <div class="mt-4">
                        <x-label for="status" :value="__('Category Status')" />
                        <select class="block mt-1 w-full" name="status">
                            <option value="1"> Enable </option>
                            <option value="0"> Disable </option>                             
                        </select>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-button class="ml-4">
                            {{ __('Add new category') }}
                        </x-button>
                    </div>
                </form>               
            </div>
            <!--/Card-->
        </div>
    </div>
@endsection