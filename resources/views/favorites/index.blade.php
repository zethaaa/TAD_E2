@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 fw-bold">Mis Favoritos <span style="color: #C0392B;">❤️</span></h2>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse($favoriteItems as $item)
            @php $product = $item->product; @endphp {{-- Accedemos al producto desde el item de la lista --}}
            
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    @endif

                    <div class="card-body">
                        <span class="badge mb-2" style="background-color: #C0392B;">
                            {{ $product->category->name ?? 'Sin categoría' }}
                        </span>
                        <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
                            <h5 class="card-title">{{ $product->name }}</h5>
                        </a>
                        <p class="card-text text-muted small">{{ Str::limit($product->description, 70) }}</p>
                        <div class="fw-bold fs-5" style="color: #C0392B;">{{ number_format($product->price, 2) }} €</div>
                    </div>

                    <div class="card-footer bg-white border-0">
                        <form action="{{ route('cart.items.store', $product->id) }}" method="POST">
                            @csrf
                            <button class="btn w-100 text-white" style="background-color: #C0392B;" type="submit">
                                Añadir al carrito
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No tienes nada guardado aún.</p>
                <a href="{{ route('products.index') }}" class="btn text-white" style="background-color: #C0392B;">Ver productos</a>
            </div>
        @endforelse
    </div>
</div>
@endsection