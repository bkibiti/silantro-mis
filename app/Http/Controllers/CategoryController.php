<?php

namespace App\Http\Controllers;

use App\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()

    {
        $categories = Category::orderBy('id', 'DESC')->get();
        return view('masters.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $category = new Category;
        $category->name = $request->name;
        $category->save();

        session()->flash("alert-success", "Product Category Added Successfully!");
        return back();
    }

    public function destroy(Request $request)
    {
        try {
            Category::destroy($request->id);
            session()->flash("alert-danger", "Product Category Deleted successfully!");
            return back();
        } catch (Exception $exception) {
            session()->flash("alert-danger", "Product Category in use!");
            return back();
        }

    }

    public function update(Request $request, $id)
    {
        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->save();
        session()->flash("alert-success", "Product category updated successfully!");
        return back();
    }

}
