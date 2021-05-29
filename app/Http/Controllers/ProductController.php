<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $products = Product::where('available', true)->get();

    if (count($products) == 0) return response()->json(null, 204);

    return response()->json($products, 200);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $req
   * @return \Illuminate\Http\Response
   */
  public function store(Request $req) {
    $user = Auth::user();

    if ($user->seller !== null) {
      $req->validate([
        'name' => 'required|string|max:30',
        'price' => 'required|numeric',
        'description' => 'string',
        'amount' => 'required|integer|numeric',
        'min_amount_purchase' => 'required|integer|numeric',
        'available' => 'boolean'
      ]);

      $product = new Product($req->all());
      $product->seller = $user->seller;
      $product->save();

      return response()->json($product, 200);
    }

    abort(401);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function show(Product $product)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $req
   * @param  \App\Models\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function update(Request $req, $id) {
    $user = Auth::user();
    $product = Product::find($id);

    if ($product == null) abort(404);

    $req->validate([
      'name' => 'string|max:30',
      'price' => 'numeric',
      'description' => 'string',
      'amount' => 'integer|numeric',
      'min_amount_purchase' => 'integer|numeric',
      'available' => 'boolean'
    ]);

    if ($product->seller == $user->seller) {
      $product->update($req->all());

      return response()->json($product, 200);
    }

    abort(401);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function destroy($id) {
    $user = Auth::user();
    $product = Product::find($id);

    if ($product == null) abort(404);

    if ($product->seller == $user->seller) {
      $product_record = $product;

      $product->delete();

      return response()->json($product_record, 200);
    }

    abort(401);
  }

  public function indexBySeller() {
    $user = Auth::user();
    $seller = Seller::find($user->seller);

    if ($seller != null) {
      $products = Product::where('seller', $seller->id)->get();

      if (count($products) == 0) return response()->json(null, 204);

      return response()->json($products, 200);
    }

    abort(404);
  }

  public function indexBySellerPublic($id) {
    $products = Product::where([
      ['seller', $id],
      ['available', true]
    ])->get();

    if (count($products) == 0) return response(null, 204);

    return $products;
  }

  public function search(Request $req) {
    $user = Auth::user();
    if ($user->seller != null) {
      $req->validate([
        'search_term' => 'required|string|max:30'
      ]);

      return Product::search($req->search_term)->get();
    }

    abort(401);
  }

  public function searchPublic(Request $req) {
    $req->validate([
      'search_term' => 'required|string|max:30'
    ]);

    return Product::search($req->input('search_term'))->where('available', 'true')->get();
  }
}
