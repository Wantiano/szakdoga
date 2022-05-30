<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Utils\AboutUs;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $baseCategories = Category::all()->take(3);
        
        $products = [];

        foreach($baseCategories as $category) {
            foreach($category->getAllActiveProducts() as $product) {
                $products[] = $product;
            }
        }

        $data = [
            'categoryName' => 'Főoldal',
            'categories' => $baseCategories, 
            'products' => $products,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('categories.show', $data);
    }

    /**
     * Show categories and products related to search text
     * 
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $search = $request->input('search');

        $products = Product::where('is_deleted', false)->where('name', 'like', '%' . $search . '%')->get();
        $categories = Category::where('name', 'like', '%' . $search . '%')->get();

        $data = [
            'search' => $search,
            'products' => $products,
            'categories' => $categories,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('home.search', $data);
    }
    
    /**
     * Show about us page.
     * 
     * @return \Illuminate\contracts\Support\Renderable
     */
    public function aboutus()
    {
        $data = [
            'paragraphs' => AboutUs::ABOUTUS,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('home.aboutus', $data);
    }
}
