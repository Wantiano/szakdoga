<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Utils\ValidatorUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if( !$user->is_admin ) abort(404);

        $categories = Category::select('name', 'id')->orderBy('name', 'ASC')->get();
        
        $data = [
            'categories' => $categories,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('products.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if( !$user->is_admin ) abort(404);

        $stockFieldsCounter = $request->input('stock_fields_counter');
        $imagesCounter = $request->input('image_fields_counter');

        $validated = $request->validate(
            ValidatorUtil::getStoreValidationRulesForProduct($stockFieldsCounter, $imagesCounter), 
            ValidatorUtil::getStoreValidationMessagesForProduct($stockFieldsCounter, $imagesCounter)
        );

        if( !ValidatorUtil::areStockFieldsUniques($validated, $stockFieldsCounter)) {
            return back()->withInput()
                         ->with('stock-unique', 'Egy szín-méret kombináció csak egyszer szerepelhet.');
        }

        $validated['is_deleted'] = false;
        $validated['created_by'] = $user->id;
        $validated['last_modified_by'] = $user->id;

        $product = Product::create($validated);

        $product->syncStocks($stockFieldsCounter, $validated);
        $product->saveProductImages($imagesCounter, $request);

        $request->session()->flash('product-updated', $product->name);
        $data = [
            'product' => $product,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return redirect()->route('products.show', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if ($product == null) abort(404);

        $orderedByColorStocks = $product->stocks->sortBy('color');

        $productIsInFavorites = false;

        if(Auth::check()) {
            $user = Auth::user();
            $productIsInFavorites = $user->favoriteProducts()->where('product_id', $id)->first() != null;
        }

        $data = [
            'product' => $product, 
            'product_is_in_favorites' => $productIsInFavorites,
            'orderedByColorStocks' => $orderedByColorStocks,
            'parentCategories' => $product->category->getParents(),
            'product_short_description' => substr($product->description,0,200) . '...',
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('products.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        if( !$user->is_admin ) abort(404);

        $product = Product::find($id);
        if ($product == null || $product->is_deleted) abort(404);

        $data = [
            'product' => $product,
            'categories' => Category::all(),
            'stocks' => $product->stocks,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('products.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if( !$user->is_admin ) abort(404);

        $product = Product::find($id);
        if ($product == null || $product->is_deleted) abort(404);

        $stockFieldsCounter = $request->input('stock_fields_counter');
        $imagesCounter = $request->input('image_fields_counter');
    
        $validated = $request->validate(
            ValidatorUtil::getUpdateValidationRulesForProduct($request, $stockFieldsCounter, $imagesCounter, count($product->images)),
            ValidatorUtil::getUpdateValidationMessagesForProduct($request, $stockFieldsCounter, $imagesCounter, count($product->images))
        );

        if( !ValidatorUtil::areStockFieldsUniques($validated, $stockFieldsCounter) ) {
            return back()->withInput()
                         ->with('stock-unique', 'Egy szín-méret kombináció csak egyszer szerepelhet');
        }

        if( !ValidatorUtil::afterOperationsHasImage($imagesCounter, $product, $request) ) {
            return back()->withInput()
                         ->with('no-image', 'A terméknek legalább egy kép kell');
        }

        $validated['last_modified_by'] = $user->id;
        $product->update($validated);

        $inputStocks = $product->syncStocks($stockFieldsCounter, $validated);
        $product->deleteIrrelevantStocks($inputStocks);
        $product->deleteRemovedImages($request);
        $product->saveProductImages($imagesCounter, $request);

        $request->session()->flash('product-updated', $product->name);
        return redirect()->route('products.show', $id);
    }

    /**
     * Mark the specified resource as deleted product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product == null || $product->is_deleted) abort(404);

        $product->setDeleted();

        return redirect()->route('categories.show', $product->category_id)->with('product-deleted', $product->name);
    }
}
