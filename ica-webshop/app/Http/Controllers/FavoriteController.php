<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Auth;

class FavoriteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display favorites connected to user
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);

        $data = [
            'favoriteProducts' => $user->favoriteProducts,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('favorites.favorites', $data);
    }

    /**
     * Attach product to user as favorite.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($id)
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);

        $product = Product::find($id);
        if(is_null($product) || $user->isProductAlreadyFavorite($product) || $product->is_deleted) abort(404);

        $user->favoriteProducts()->attach($id);

        return back()->with('favorite-created', $product->name);
    }

    /**
     * Remove favorite relationship.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);

        $product = Product::find($id);
        if(is_null($product) || !$user->isProductAlreadyFavorite($product)) abort(404);

        $user->favoriteProducts()->detach($id);

        return back()->with('favorite-deleted', $product->name);
    }
}
