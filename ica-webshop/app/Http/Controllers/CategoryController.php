<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Utils\FileUtil;
use App\Utils\ValidatorUtil;

class CategoryController extends Controller
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
        return view('categories.create', $data);
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

        $validated = $request->validate(
            ValidatorUtil::getValidationRulesForCategories(), 
            ValidatorUtil::getValidationMessagesForCategories()
        );

        $validated['image_url'] = FileUtil::storeFile($request->file('cover_image'), 'images/category_covers/');
        $validated['created_by'] = $user->id;
        $validated['last_modified_by'] = $user->id;

        $category = Category::create($validated);

        $data = [
            'category' => $category->id,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return redirect()->route('categories.show', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        if(is_null($category)) abort(404);

        $data = [
            'categoryName' => $category->name,
            'categories' => $category->children, 
            'products' => $category->getAllActiveProducts(), 
            'parentCategories' => $category->getParents(),
            'category_id' => $category->id,
            'is_base_category' => is_null($category->parentCategory),
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('categories.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        if(is_null($category)) abort(404);

        $categories = Category::select('name', 'id')->orderBy('name', 'ASC')->get();

        $data = [
            'is_base_category' => is_null($category->parentCategory),
            'editable_category' => $category, 
            'categories' => $categories,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('categories.edit', $data);
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

        $category = Category::find($id);
        if( is_null($category) ) abort(404);

        $validator = [ValidatorUtil::getValidationRulesForCategories(), ValidatorUtil::getValidationMessagesForCategories()];

        if($category->isBaseCategory()) {
            // Can not change base category's parent
            if($request->has('parent_category_id')) abort(404);
            // Remove validation rule for parent category
            unset($validator[0]['parent_category_id']);
        }

        if( !$request->has('cover_image') ) {
            // Remove validation rule for cover image
            unset($validator[0]['cover_image']);
        }

        $validated = $request->validate($validator[0], $validator[1]);
        $validated['last_modified_by'] = $user->id;

        if($request->has('cover_image')) {
            if(!is_null($category->image_url)) {
                $category->deleteCoverImageFile();
            }
            $validated['image_url'] = FileUtil::storeFile($request->file('cover_image'), 'images/category_covers/');    
        }

        $category->update($validated);

        $request->session()->flash('category-updated', true);
        $data = [
            'category' => $category->id,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return redirect()->route('categories.show', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $user = Auth::user();
        if( !$user->is_admin ) abort(404);

        $category = Category::find($id);
        if(is_null($category) || $category->isBaseCategory()) abort(404);

        $category->reassignCategoriesToParent();
        $category->reassignProductsToParent();
        $category->deleteCoverImageFile();
        $category->delete();

        $request->session()->flash('category-deleted', $category->name);
        $data = [
            'category' => $category->parent_category_id,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return redirect()->route('categories.show', $data);
    }
}
