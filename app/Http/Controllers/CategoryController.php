<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        return view('categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category) {
        return view('categories.show', ['category' => $category]);
    }
}
