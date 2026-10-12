<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('profile')
            ->withCount('stockMovements as movement_count')
            ->orderBy('id')
            ->get();

        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,staff'],
            'full_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'username' => strtolower($validated['username']),
                'password' => $validated['password'],
                'role' => $validated['role'],
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);
        });

        return redirect()->route('users.index')
            ->with('success', "Pengguna '{$validated['username']}' berhasil ditambahkan.");
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:admin,staff'],
            'full_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($user, $validated) {
            $updateData = [
                'username' => strtolower($validated['username']),
                'role' => $validated['role'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = $validated['password'];
            }

            $user->update($updateData);

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => $validated['full_name'],
                    'phone' => $validated['phone'] ?? null,
                    'address' => $validated['address'] ?? null,
                ]
            );
        });

        return redirect()->route('users.index')
            ->with('success', "Data pengguna '{$validated['username']}' berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('danger', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        if ($user->stockMovements()->exists()) {
            return back()->with('danger', 'Pengguna tidak dapat dihapus karena memiliki riwayat pencatatan transaksi mutasi barang.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
