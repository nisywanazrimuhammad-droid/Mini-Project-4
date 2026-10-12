@extends('layouts.main')

@section('title', 'Categories')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Categories</h1>
    <a href="{{ route('categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition mb-4 inline-block">Add New Category</a>
    @if($categories->isEmpty())
        <p class="mt-4">No categories found.</p>
    @else
        <table class="min-w-full bg-white shadow rounded mt-4">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Description</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $category->id }}</td>
                    <td class="px-4 py-2">{{ $category->name }}</td>
                    <td class="px-4 py-2">{{ $category->description }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('categories.show', $category) }}" class="text-indigo-600 hover:underline mr-2">View</a>
                        <a href="{{ route('categories.edit', $category) }}" class="text-green-600 hover:underline mr-2">Edit</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this category?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
