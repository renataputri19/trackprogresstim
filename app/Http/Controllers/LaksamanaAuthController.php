<?php

namespace App\Http\Controllers;

use App\Models\LaksamanaUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LaksamanaAuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('laksamana.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:laksamana_users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = LaksamanaUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('laksamana')->login($user);

        return redirect()->route('laksamana.index')->with('success', 'Pendaftaran berhasil! Akun Anda telah dibuat.');
    }

    public function showLoginForm()
    {
        return view('laksamana.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('laksamana')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('laksamana.index')->with('success', 'Masuk berhasil.');
        }

        return back()->withErrors(['email' => 'Email atau password tidak sesuai.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('laksamana')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('laksamana.index')->with('success', 'Anda telah keluar dari akun Laksamana.');
    }
}