@props(['errors'])
<div id="ajax_msg" class="text-center"></div>

@if ($errors)
    <div class="text-center" {{ $attributes }}>      
        <ul class="mt-3 list-none list-inside text-sm text-red-600">
            @if (is_array($errors))
                @foreach ($errors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            @elseif(is_string($errors))
                <li>{{ $errors }}</li>
            @elseif($errors && $errors->any())
                <li>{{ $errors }}</li>
            @endif
        </ul>
    </div>
@endif
