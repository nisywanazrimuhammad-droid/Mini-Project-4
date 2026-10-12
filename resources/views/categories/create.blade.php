@extends('layouts.main')

@section('title', 'Create Category')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Add New Category</h1>
    <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block font-medium mb-1" for="name">Name</label>
            <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-medium mb-1" for="description">Description</label>
            <textarea name="description" id="description" class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Create</button>
        <a href="{{ route('categories.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
