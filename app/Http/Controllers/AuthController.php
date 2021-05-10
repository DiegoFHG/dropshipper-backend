<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
  public function register(Request $req) {
    $req->validate([
      'first_name' => 'required|string',
      'last_name' => 'required|string',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:8'
    ]);

    $user = User::create($req->all());

    return response()->json($user, 200);
  }

  public function login(Request $req) {
    $credentials = $req->only('email', 'password');

    if (Auth::attempt($credentials)) {
      return response(Auth::user(), 200);
    }

    abort(401);
  }

  public function logout() {
    Auth::logout();

    return response(null, 200);
  }
}
