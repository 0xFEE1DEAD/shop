<div>
    ({{ $category->id }}) {{ $category->name }} (order {{ $category->order }})
    <div style="margin-left: 25px">
        @foreach ($category->products as $product)
            <div style="color: green"> ({{ $product->id }}) {{ $product->name }} {{ $product->price }} (order {{ $product->order }})</div>
        @endforeach
        @component('categories-tree', ['categories' => $category->children_with_products])
        @endcomponent
    </div>
</div>