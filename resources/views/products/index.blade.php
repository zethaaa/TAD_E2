@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Productos</h2>
    <a href="{{ route('products.create') }}" class="btn btn-danger">+ Nuevo producto</a>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>SKU</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category->name ?? 'Sin categoría' }}</td>
            <td>{{ number_format($product->price, 2) }} €</td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->sku }}</td>
            <td>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Editar</a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger btn-sm" type="submit"
                        onclick="return confirm('¿Eliminar producto?')">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $products->links() }}
@endsection