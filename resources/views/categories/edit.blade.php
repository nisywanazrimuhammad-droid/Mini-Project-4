@extends('layouts.main')

@section('title', 'Edit Category')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Category</h1>
    <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block font-medium mb-1" for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-medium mb-1" for="description">Description</label>
            <textarea name="description" id="description" class="w-full border rounded px-3 py-2">{{ old('description', $category->description) }}</textarea>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save Changes</button>
        <a href="{{ route('categories.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
