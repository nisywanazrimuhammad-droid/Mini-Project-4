@extends('layouts.main')

@section('title', 'Suppliers')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Suppliers</h1>
    <a href="{{ route('suppliers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition mb-4 inline-block">Add New Supplier</a>
    @if($suppliers->isEmpty())
        <p class="mt-4">No suppliers found.</p>
    @else
        <table class="min-w-full bg-white shadow rounded mt-4">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Contact</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $supplier)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $supplier->id }}</td>
                    <td class="px-4 py-2">{{ $supplier->name }}</td>
                    <td class="px-4 py-2">{{ $supplier->contact }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('suppliers.show', $supplier) }}" class="text-indigo-600 hover:underline mr-2">View</a>
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="text-green-600 hover:underline mr-2">Edit</a>
                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this supplier?');">
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
</div>
@endsection
