<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $req
   * @return \Illuminate\Http\Response
   */
  public function store(Request $req) {
    $user = Auth::user();

    if ($user->seller == null) {
      $req->validate([
        'name' => 'required|string|unique:sellers',
        'dni' => 'required|string|max:9',
        'address' => 'required|string',
        'phone_number' => 'required|string'
      ]);

      $seller = new Seller($req->all());
      $seller->user = $user->id;
      $seller->save();

      $user->seller = $seller->id;
      $user->save();

      return response()->json($seller, 200);
    }

    abort(409);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Seller  $seller
   * @return \Illuminate\Http\Response
   */
  public function show() {
    $seller = Seller::find(Auth::user()->seller);

    if ($seller != null) {
      return $seller;
    }

    return response(null, 404);;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $req
   * @param  \App\Models\Seller  $seller
   * @return \Illuminate\Http\Response
   */
  public function update(Request $req, Seller $seller) {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Seller  $seller
   * @return \Illuminate\Http\Response
   */
  public function destroy(Seller $seller) {
    //
  }
}
