@extends('layouts.main')

@section('title', 'Edit Product')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Product</h1>
    <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block font-medium mb-1" for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-medium mb-1" for="description">Description</label>
            <textarea name="description" id="description" class="w-full border rounded px-3 py-2">{{ old('description', $product->description) }}</textarea>
        </div>
        <div>
            <label class="block font-medium mb-1" for="price">Price</label>
            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-medium mb-1" for="stock">Stock Quantity</label>
            <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-medium mb-1" for="category_id">Category</label>
            <select name="category_id" id="category_id" class="w-full border rounded px-3 py-2" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block font-medium mb-1" for="suppliers">Suppliers</label>
            <select name="suppliers[]" id="suppliers" multiple class="w-full border rounded px-3 py-2">
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $product->suppliers->contains($supplier->id) ? 'selected' : '' }}>{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save Changes</button>
        <a href="{{ route('products.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
