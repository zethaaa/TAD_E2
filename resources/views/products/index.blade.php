@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Productos</h2>
    @if(auth()->check() && auth()->user()->role_id === 1)
        <a href="{{ route('products.create') }}" class="btn btn-danger">+ Nuevo producto</a>
    @endif
</div>

<form method="GET" action="{{ route('products.index') }}" class="mb-4">
    <div class="d-flex gap-2 align-items-center">
        <select name="category_id" class="form-select w-auto">
            <option value="">Todas las categorías</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-danger">Filtrar</button>
        @if(request('category_id'))
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Limpiar</a>
        @endif
    </div>
</form>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row row-cols-1 row-cols-md-3 g-4">
    @forelse($products as $product)
    <div class="col">
        <div class="card h-100 shadow-sm">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
            @else
                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                    <span class="text-white">Sin imagen</span>
                </div>
            @endif

            <div class="card-body">
                <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
    <h5 class="card-title">{{ $product->name }}</h5>
</a>
                <p class="card-text text-muted small">{{ Str::limit($product->description, 80) }}</p>
                <span class="badge mb-2" style="background-color: #C0392B;">
                    {{ $product->category->name ?? 'Sin categoría' }}
                </span>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h5 class="mb-0 fw-bold">{{ number_format($product->price, 2) }} €</h5>
                    <span class="text-muted small">Stock: {{ $product->stock }}</span>
                </div>
            </div>

            <div class="card-footer bg-white">
                @if(auth()->check() && auth()->user()->role_id === 1)
                    <div class="d-flex gap-2">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm flex-fill">Editar</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="flex-fill">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger btn-sm w-100" type="submit"
                                onclick="return confirm('¿Eliminar producto?')">Eliminar</button>
                        </form>
                    </div>
                @else
                    @if($product->stock > 0)
                        <button class="btn w-100 text-white" style="background-color: #C0392B;">
                            Añadir al carrito
                        </button>
                    @else
                        <button class="btn btn-secondary w-100" disabled>Sin stock</button>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @empty
        <div class="col-12">
            <p class="text-muted">No hay productos en esta categoría.</p>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection