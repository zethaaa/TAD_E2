@extends('layouts.app')

@section('content')
<h2 class="mb-4">Gestión de pedidos</h2>

@if($orders->isEmpty())
    <div class="alert alert-info">No hay pedidos todavía.</div>
@else
    <div class="table-responsive">
        <table class="table shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Nº Pedido</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th class="text-end">Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
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
                <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->ordered_at)->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge bg-{{ $badges[$order->status] }}">
                            {{ $labels[$order->status] }}
                        </span>
                    </td>
                    <td class="text-end fw-bold">{{ number_format($order->total_amount, 2) }} €</td>
                    <td class="text-end">
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-danger">Ver</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection