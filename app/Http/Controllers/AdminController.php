<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role', $request->string('role'));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.index', [
            'users' => $users,
            'roleFilter' => $request->input('role'),
        ]);
    }

    public function verify(User $user)
    {
        $user->update(['is_verified' => true]);

        return redirect()->route('admin.index')->with('status', 'User marked as verified.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return back()->withErrors(['user' => 'You cannot remove your own account.']);
        }

        $user->delete();

        return redirect()->route('admin.index')->with('status', 'User removed.');
    }
}
