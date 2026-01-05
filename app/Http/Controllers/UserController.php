<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\ReturnFactory;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));

    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,customer',
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'Role user berhasil diperbarui.');
    }

    public function destroyrole(User $user){
        $user->delete();

        return redirect()->route('users.index')->with('sukses','user berhasil dihapus');
    }

}
