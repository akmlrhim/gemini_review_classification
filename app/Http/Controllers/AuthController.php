<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function index()
	{
		$title = 'Login';
		return view('auth.index', compact('title'));
	}

	public function login(Request $request)
	{
		$request->validate([
			'email' => 'required|email',
			'password' => 'required'
		]);

		$user = User::where('email', $request->email)->first();

		if (!$user) {
			return back()->withInput()->with('error', 'Email tidak ditemukan.');
		}

		if (!Hash::check($request->password, $user->password)) {
			return back()->withInput()->with('error', 'Password salah.');
		}

		if ($user->status !== 'active') {
			return back()->withInput()->with('error', 'Akun Anda belum aktif. Silakan hubungi admin.');
		}

		Auth::login($user);
		$request->session()->regenerate();

		return redirect()->intended('dashboard');
	}

	public function logout(Request $request)
	{
		Auth::logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		$request->session()->flush();
		return redirect()->route('login')->with('info', 'Anda telah logout!');
	}

	public function register()
	{
		$title = 'Register';
		return view('auth.register', compact('title'));
	}

	public function saveRegister(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|string|min:8'
		]);

		DB::table('users')->insert([
			'name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
			'role' => 'user',
			'status' => 'active',
			'created_at' => now(),
			'updated_at' => now()
		]);

		return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login!');
	}
}
