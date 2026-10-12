@extends('layouts.main')

@section('title', 'Category Details')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6">{{ $category->name }}</h1>
    <p><strong>Description:</strong> {{ $category->description }}</p>
    <p><strong>Products in this category:</strong>
        @if($category->products->isEmpty())
            None
        @else
            {{ $category->products->pluck('name')->join(', ') }}
        @endif
    </p>
    <div class="mt-6 space-x-4">
        <a href="{{ route('categories.edit', $category) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Edit</a>
        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this category?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
        </form>
        <a href="{{ route('categories.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Back to List</a>
    </div>
</div>
@endsection
