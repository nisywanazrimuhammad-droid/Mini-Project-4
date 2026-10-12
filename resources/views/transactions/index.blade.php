@extends('layouts.main')

@section('title', 'Transactions')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Transactions</h1>
    @if($transactions->isEmpty())
        <p>No transactions recorded.</p>
    @else
        <table class="min-w-full bg-white shadow rounded mt-4">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Product</th>
                    <th class="px-4 py-2 text-left">Quantity</th>
                    <th class="px-4 py-2 text-left">Type</th>
                    <th class="px-4 py-2 text-left">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $txn)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $txn->id }}</td>
                    <td class="px-4 py-2">{{ $txn->product->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $txn->quantity }}</td>
                    <td class="px-4 py-2">{{ ucfirst($txn->type) }}</td>
                    <td class="px-4 py-2">{{ $txn->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
