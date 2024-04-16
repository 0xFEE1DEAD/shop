@foreach($categories as $category)
    @component('category', compact('category'))
    @endcomponent
@endforeach