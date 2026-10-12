@extends('layouts.main')

@section('title', 'Product Details')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6">{{ $product->name }}</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <p><strong>Description:</strong> {{ $product->description }}</p>
            <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
            <p><strong>Stock:</strong> {{ $product->stock }}</p>
            <p><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
            <p><strong>Suppliers:</strong>
                @if($product->suppliers->isEmpty())
                    None
                @else
                    {{ $product->suppliers->pluck('name')->join(', ') }}
                @endif
            </p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('products.edit', $product) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Edit</a>
            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
            </form>
            <a href="{{ route('products.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Back to List</a>
        </div>
    </div>
</div>
@endsection
