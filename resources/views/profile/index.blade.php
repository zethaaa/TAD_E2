@extends('layouts.app')

@section('content')
<div class="row">
    {{-- SIDEBAR --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body text-center py-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:80px;height:80px;background-color:#C0392B;">
                    <span class="text-white fw-bold fs-3">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                <h6 class="fw-bold mb-0">{{ auth()->user()->name }}</h6>
                <small class="text-muted">{{ auth()->user()->email }}</small>
            </div>
        </div>
        <div class="list-group shadow-sm">
            <a href="#datos" class="list-group-item list-group-item-action {{ session('active_tab', 'datos') == 'datos' ? 'active' : '' }}"
               style="{{ session('active_tab', 'datos') == 'datos' ? 'background-color:#C0392B;border-color:#C0392B;' : '' }}"
               data-bs-toggle="list">
                👤 Datos personales
            </a>
            <a href="#password" class="list-group-item list-group-item-action {{ session('active_tab') == 'password' ? 'active' : '' }}"
               style="{{ session('active_tab') == 'password' ? 'background-color:#C0392B;border-color:#C0392B;' : '' }}"
               data-bs-toggle="list">
                🔑 Cambiar contraseña
            </a>
            <a href="#direcciones" class="list-group-item list-group-item-action {{ session('active_tab') == 'direcciones' ? 'active' : '' }}"
               style="{{ session('active_tab') == 'direcciones' ? 'background-color:#C0392B;border-color:#C0392B;' : '' }}"
               data-bs-toggle="list">
                📍 Direcciones
            </a>
            <a href="#pagos" class="list-group-item list-group-item-action {{ session('active_tab') == 'pagos' ? 'active' : '' }}"
               style="{{ session('active_tab') == 'pagos' ? 'background-color:#C0392B;border-color:#C0392B;' : '' }}"
               data-bs-toggle="list">
                💳 Métodos de pago
            </a>
            <a href="#pedidos" class="list-group-item list-group-item-action {{ session('active_tab') == 'pedidos' ? 'active' : '' }}"
               style="{{ session('active_tab') == 'pedidos' ? 'background-color:#C0392B;border-color:#C0392B;' : '' }}"
               data-bs-toggle="list">
                📦 Mis pedidos
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="list-group-item list-group-item-action text-danger w-100 text-start">
                    🚪 Cerrar sesión
                </button>
            </form>
        </div>
    </div>

    {{-- CONTENIDO --}}
    <div class="col-md-9">
        <div class="tab-content">

            {{-- DATOS PERSONALES --}}
            <div class="tab-pane fade {{ session('active_tab', 'datos') == 'datos' ? 'show active' : '' }}" id="datos">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Datos personales</h5>
                        <form action="{{ route('profile.updateInfo') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}">
                            </div>
                            <button class="btn text-white" style="background-color:#C0392B;">Guardar cambios</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- CONTRASEÑA --}}
            <div class="tab-pane fade {{ session('active_tab') == 'password' ? 'show active' : '' }}" id="password">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Cambiar contraseña</h5>
                        <form action="{{ route('profile.updatePassword') }}" method="POST">
                            @csrf
                            @error('current_password')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            @error('password')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="mb-3">
                                <label class="form-label">Contraseña actual</label>
                                <input type="password" name="current_password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nueva contraseña</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmar nueva contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                            <button class="btn text-white" style="background-color:#C0392B;">Actualizar contraseña</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- DIRECCIONES --}}
            <div class="tab-pane fade {{ session('active_tab') == 'direcciones' ? 'show active' : '' }}" id="direcciones">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Mis direcciones</h5>
                        @forelse($addresses as $address)
                        <div class="border rounded p-3 mb-3 d-flex justify-content-between align-items-start">
                            <div>
                                @if($address->is_default)
                                    <span class="badge mb-1" style="background-color:#C0392B;">Principal</span><br>
                                @endif
                                <strong>{{ $address->street }}</strong><br>
                                {{ $address->postal_code }} {{ $address->city }}
                                @if($address->state), {{ $address->state }}@endif<br>
                                {{ $address->country }}
                            </div>
                            <form action="{{ route('profile.destroyAddress', $address->id) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar dirección?')">Eliminar</button>
                            </form>
                        </div>
                        @empty
                            <p class="text-muted">No tienes direcciones guardadas.</p>
                        @endforelse
                        <hr>
                        <hr>
                        <h6 class="fw-bold mb-3">Añadir dirección</h6>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('profile.storeAddress') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Calle y número</label>
                                <input type="text" name="street" class="form-control" 
                                placeholder="Calle Mayor 1" value="{{ old('street') }}">
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">Ciudad</label>
                                    <input type="text" name="city" class="form-control" 
                                    value="{{ old('city') }}">
                                </div>
                                <div class="col mb-3">
                                    <label class="form-label">Código postal</label>
                                    <input type="text" name="postal_code" class="form-control" 
                                    value="{{ old('postal_code') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">Provincia</label>
                                    <input type="text" name="state" class="form-control" 
                                    value="{{ old('state') }}">
                                </div>
                                <div class="col mb-3">
                                    <label class="form-label">País</label>
                                    <input type="text" name="country" class="form-control" 
                                    value="{{ old('country', 'España') }}">
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" name="is_default" class="form-check-input" id="is_default">
                                <label class="form-check-label" for="is_default">Establecer como dirección principal</label>
                            </div>
                            <button class="btn text-white" style="background-color:#C0392B;">Añadir dirección</button>
                        </form>
                    </div>
                </div>
            </div>

           {{-- MÉTODOS DE PAGO --}}
<div class="tab-pane fade {{ session('active_tab') == 'pagos' ? 'show active' : '' }}" id="pagos">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4">Métodos de pago</h5>
            <div class="alert alert-info d-flex align-items-center gap-2">
                <span>💳</span>
                <span>La integración con pasarela de pago está pendiente de implementación. Próximamente podrás gestionar tus métodos de pago aquí.</span>
            </div>
        </div>
    </div>
</div>
          {{-- PEDIDOS --}}
<div class="tab-pane fade {{ session('active_tab') == 'pedidos' ? 'show active' : '' }}" id="pedidos">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4">Mis pedidos</h5>
            @if($orders->isEmpty())
                <p class="text-muted">No tienes pedidos todavía.</p>
            @else
                @foreach($orders as $order)
                <div class="border rounded p-3 mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $order->order_number }}</strong><br>
                        <small class="text-muted">{{ $order->ordered_at->format('d/m/Y') }}</small>
                    </div>
                    <div class="text-end">
                        <span class="fw-bold">{{ number_format($order->total_amount, 2) }} €</span><br>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-danger mt-1">Ver detalle</a>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

        </div>
    </div>
</div>
@endsection