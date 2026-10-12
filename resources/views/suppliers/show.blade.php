@extends('layouts.main')

@section('title', 'Supplier Details')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6">{{ $supplier->name }}</h1>
    <p><strong>Contact:</strong> {{ $supplier->contact }}</p>
    <p><strong>Products Supplied:</strong>
        @if($supplier->products->isEmpty())
            None
        @else
            {{ $supplier->products->pluck('name')->join(', ') }}
        @endif
    </p>
    <div class="mt-6 space-x-4">
        <a href="{{ route('suppliers.edit', $supplier) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Edit</a>
        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this supplier?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
        </form>
        <a href="{{ route('suppliers.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Back to List</a>
    </div>
</div>
@endsection
