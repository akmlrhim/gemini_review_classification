<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
	public function index()
	{
		$title = 'User Management';
		$users = DB::table('users')
			->select('id', 'name', 'email', 'role', 'status', 'created_at')
			->where('id', '!=', Auth::id())
			->paginate(10);

		return view('manage-user.index', compact('title', 'users'));
	}

	public function create()
	{
		$title = 'Add User';
		return view('manage-user.create', compact('title'));
	}

	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|string|min:8|confirmed',
			'role' => 'required|in:admin,user'
		]);

		DB::table('users')->insert([
			'name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
			'role' => $request->role,
			'status' => $request->status
		]);

		return redirect()->route('manage-user.index')->with('success', 'User berhasil ditambahkan!');
	}

	public function edit($id)
	{
		$title = 'Edit User';
		$user = DB::table('users')->where('id', $id)->first();

		return view('manage-user.edit', compact('title', 'user'));
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email,' . $id,
			'role' => 'required|in:admin,user'
		]);

		DB::table('users')->where('id', $id)->update([
			'name' => $request->name,
			'email' => $request->email,
			'status' => $request->status,
			'role' => $request->role,
		]);

		return redirect()->route('manage-user.index')->with('success', 'User berhasil diperbarui!');
	}

	public function destroy($id)
	{
		DB::table('users')->where('id', $id)->delete();
		return redirect()->route('manage-user.index')->with('success', 'User berhasil dihapus!');
	}
}
