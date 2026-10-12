@extends('layouts.main')

@section('title', 'User Management')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Users</h1>
    <a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition mb-4 inline-block">Add New User</a>
    @if($users->isEmpty())
        <p class="mt-4">No users found.</p>
    @else
        <table class="min-w-full bg-white shadow rounded mt-4">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $user->id }}</td>
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">{{ $user->isAdmin() ? 'Admin' : 'User' }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('users.show', $user) }}" class="text-indigo-600 hover:underline mr-2">View</a>
                        <a href="{{ route('users.edit', $user) }}" class="text-green-600 hover:underline mr-2">Edit</a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this user?');">
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
