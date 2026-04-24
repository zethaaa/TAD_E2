@extends('layouts.app')

@section('content')
<h2 class="mb-4">Nueva categoría</h2>

<form action="{{ route('categories.store') }}" method="POST">
    @csrf

    @error('name')<div class="alert alert-danger">{{ $message }}</div>@enderror

    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
        <small class="text-muted">El slug se genera automáticamente</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
    </div>

    <button type="submit" class="btn btn-danger">Guardar categoría</button>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection