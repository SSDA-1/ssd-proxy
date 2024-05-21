<?php

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\User;
use Ssda1\proxies\Models\Referral;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Hash;

class CustomAuthController extends Controller
{
    public function loginForm(Request $request)
    {
        $referralCode = $request->filled('referral_code') ? $request->input('referral_code') : null;

        // Проверяем, существует ли реферальный код в GET-параметрах запроса
        if ($referralCode) {
            // Сохраняем реферальный код в сессию
            Session::put('referral_code', $referralCode);
        }

        return view('proxies::auth.login')->withErrors([
            'error' => 'Неправильное имя пользователя или пароль.'
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            Auth::user()->touch();

            return redirect()->intended('/');
        } else {

            return back()->withErrors([
                'error' => 'Неправильное имя пользователя или пароль.'
            ]);
        }
    }

    public function registrationForm()
    {
        return view('proxies::auth.register')->withErrors([
            'error' => 'Неправильное имя пользователя или пароль.'
        ]);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $referralCodeGet = null;

        // Проверяем, существует ли реферальный код в сессии
        if (Session::has('referral_code')) {
            // Получаем реферальный код из сессии
            $referralCodeGet = Session::get('referral_code');
        }

        $referralCode = Str::random(8);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'referral_code' => $referralCode,
        ]);

        if (Session::has('referral_code')) {
            $refs = User::where('referral_code', $referralCodeGet)->first();
            // Создаем новую запись Referral
            $referral = Referral::create([
                'user_id' => $user->id, // ID пользователя, который был приглашен
                'referred_by' => $refs->id, // ID пользователя, который пригласил
                'referral_code' => $referralCodeGet, // Сам код
            ]);
            $refs->save();
        }

        Auth::login($user);

        return redirect('/')->withErrors([
            'error' => 'Неправильное имя пользователя или пароль.'
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }
}
