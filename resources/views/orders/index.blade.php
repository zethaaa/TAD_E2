@extends('layouts.app')

@section('content')
<h2 class="mb-4">Mis pedidos</h2>

@if($orders->isEmpty())
    <div class="alert alert-info">No tienes pedidos todavía.</div>
    <a href="{{ route('products.index') }}" class="btn btn-danger">Explorar productos</a>
@else
    <div class="table-responsive">
        <table class="table shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Nº Pedido</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th class="text-end">Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    {{ \Carbon\Carbon::parse($order->ordered_at)->format('d/m/Y H:i') }}
                    <td>
                        @php
                            $badges = [
                                'pending'    => 'warning',
                                'processing' => 'info',
                                'shipped'    => 'primary',
                                'delivered'  => 'success',
                                'cancelled'  => 'danger',
                            ];
                            $labels = [
                                'pending'    => 'Pendiente',
                                'processing' => 'En proceso',
                                'shipped'    => 'Enviado',
                                'delivered'  => 'Entregado',
                                'cancelled'  => 'Cancelado',
                            ];
                        @endphp
                        <span class="badge bg-{{ $badges[$order->status] }}">
                            {{ $labels[$order->status] }}
                        </span>
                    </td>
                    <td class="text-end fw-bold">{{ number_format($order->total_amount, 2) }} €</td>
                    <td class="text-end">
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-danger">Ver detalle</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection