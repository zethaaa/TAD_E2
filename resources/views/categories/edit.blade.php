@extends('layouts.app')

@section('content')
<h2 class="mb-4">Editar categoría — {{ $category->name }}</h2>

<form action="{{ route('categories.update', $category->id) }}" method="POST">
    @csrf
    @method('PUT')

    @error('name')<div class="alert alert-danger">{{ $message }}</div>@enderror

    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
        <small class="text-muted">Slug actual: <code>{{ $category->slug }}</code></small>
    </div>

    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
    </div>

    <button type="submit" class="btn btn-danger">Actualizar categoría</button>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection