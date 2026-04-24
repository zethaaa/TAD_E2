@extends('layouts.app')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#C0392B;">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}" style="color:#C0392B;">Productos</a></li>
        <li class="breadcrumb-item active">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row g-5">
    {{-- Imagen --}}
    <div class="col-md-6">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}"
                 class="img-fluid rounded shadow"
                 style="width: 100%; max-height: 450px; object-fit: cover;">
        @else
            <div class="bg-light rounded d-flex align-items-center justify-content-center shadow"
                 style="height: 450px;">
                <span class="text-muted" style="font-size: 5rem;">🖼️</span>
            </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="col-md-6">
        <span class="badge mb-3" style="background-color:#C0392B; font-size: 0.85rem;">
            {{ $product->category->name ?? 'Sin categoría' }}
        </span>

        <h1 class="fw-bold mb-2">{{ $product->name }}</h1>

        <p class="text-muted mb-4">{{ $product->description }}</p>

        <div class="mb-4">
            <span class="fs-2 fw-bold" style="color:#C0392B;">
                {{ number_format($product->price, 2) }} €
            </span>
        </div>

        <div class="mb-4">
            @if($product->stock > 0)
                <span class="text-success fw-semibold">✔ En stock ({{ $product->stock }} disponibles)</span>
            @else
                <span class="text-danger fw-semibold">✘ Sin stock</span>
            @endif
        </div>

        @if($product->stock > 0)
            <button class="btn btn-lg w-100 text-white mb-3" style="background-color:#C0392B;">
                🛒 Añadir al carrito
            </button>
        @else
            <button class="btn btn-lg w-100 btn-secondary mb-3" disabled>Sin stock</button>
        @endif

        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100">
            ← Volver a productos
        </a>

        @if(auth()->check() && auth()->user()->role_id === 1)
            <hr>
            <div class="d-flex gap-2 mt-2">
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning flex-fill">Editar</a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="flex-fill">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger w-100" onclick="return confirm('¿Eliminar producto?')">Eliminar</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection