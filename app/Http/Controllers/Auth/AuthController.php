<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function login()
    {
        return Inertia::render("Auth/Login");
    }

    public function makeLogin(LoginRequest $request)
    {
        try {

            $logado = Auth::attempt([
                "email" => $request->input("email"),
                "password" => $request->input("password"),
            ]);

            if(empty($logado)) {
                return redirect()->back()->with([
                    "error" => "Usuário ou senha inválidos"
                ]);
            }

            return redirect()->route("dashboard");

        } catch (\Exception $e) {
            return redirect()->back()->with([
                "error" => "Não foi possível logar, tente mais tarde!"
            ]);
        }
    }

    public function register()
    {
        return Inertia::render("Auth/Register");
    }

    public function makeRegister(RegisterRequest $request)
    {
        try {

            (new Tenant())->register($request->all());

            $auth = Auth::attempt([
                "email" => $request->input("email"),
                "password" => $request->input("password"),
            ]);

            if(empty($auth)) {
                return redirect()->route("login");
            }

            return redirect()->route("dashboard.students");
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "error" => "Não foi possível realizar o cadastro!"
            ]);
        }

    }

    public function logout()
    {
        Auth::guard()->logout();
        return redirect()->route("login");
    }
}
