@extends('layouts.main')

@section('title', 'Add Supplier')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Add New Supplier</h1>
    <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block font-medium mb-1" for="name">Name</label>
            <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-medium mb-1" for="contact">Contact Information</label>
            <input type="text" name="contact" id="contact" class="w-full border rounded px-3 py-2">
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Create Supplier</button>
        <a href="{{ route('suppliers.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
