@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Categorías</h2>
    @if(auth()->check() && auth()->user()->role_id === 1)
        <a href="{{ route('categories.create') }}" class="btn btn-danger">+ Nueva categoría</a>
    @endif
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Slug</th>
            <th>Descripción</th>
            <th>Productos</th>
            @if(auth()->check() && auth()->user()->role_id === 1)
                <th>Acciones</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td><code>{{ $category->slug }}</code></td>
            <td>{{ Str::limit($category->description, 50) }}</td>
            <td>{{ $category->products->count() }}</td>
            @if(auth()->check() && auth()->user()->role_id === 1)
                <td>
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-danger btn-sm" type="submit"
                            onclick="return confirm('¿Eliminar categoría?')">Eliminar</button>
                    </form>
                </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>

{{ $categories->links() }}
@endsection