<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
    <form action="/products/restore" method="POST">
        @csrf
        
        <ul>
            @foreach ($products as $product)
            <li>
                <input name="products[]" id="product_checkbox_{{$product->id}}" type="checkbox" value="{{$product->id}}">
                <label for="product_checkbox_{{$product->id}}">{{$product->title}}</label>
            </li>
            @endforeach
            <input type="submit" value="delete">
        </ul>
    </form>
</x-layout._layout>