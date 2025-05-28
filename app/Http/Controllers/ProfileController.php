<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
	public function index()
	{
		$title = 'Profile';
		return view('profile.index', compact('title'));
	}

	public function update(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
		]);

		$user = Auth::user();

		if ($user instanceof User) {
			$user->name = $request->name;
			$user->email = $request->email;
			$user->save();

			return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
		}
	}

	public function updatePassword(Request $request)
	{
		$request->validate(
			[
				'current_password' => 'required',
				'new_password' => 'required|min:8',
			]
		);

		$user = Auth::user();

		if (!Hash::check($request->current_password, $user->password)) {
			return redirect()->back()->with('error', 'Password saat ini salah.')->withInput();
		}

		if ($user instanceof User) {
			$user->password = Hash::make($request->new_password);
			$user->save();

			return redirect()->back()->with('success', 'Password berhasil diperbarui.');
		}
	}
}
