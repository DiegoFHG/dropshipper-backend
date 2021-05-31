<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {
  public function register(Request $req) {
    $validations = Validator::make($req->all(), [
      'first_name' => 'required|string',
      'last_name' => 'required|string',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:8'
    ]);

    if ($validations->fails()) {
      return response()->json($validations->errors(), 400);
    } else {
      $user = User::create([
        'first_name' => $req->input('first_name'),
        'last_name' => $req->input('last_name'),
        'email' => $req->input('email'),
        'password' => Hash::make($req->input('password'))
      ]);

    return response()->json($user, 200);
    }
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
