<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\ProductStoreRequest;
use App\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {

        $products = Product::all();
        $category = Category::all();

        return view('masters.products.index')->with(['products' => $products,
            'categories' => $category]);
    }


    public function store(ProductStoreRequest $request)
    {

        $request->validated();

        $product = new Product;
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->purchase_uom = $request->purchase_uom;
        $product->quantity_per_unit = $request->quantity_per_unit;
        $product->min_quantinty = $request->min_quantinty;
        $product->reorder_level = $request->reorder_level;
        $product->save();

        session()->flash("alert-success", "Product added successfully!");
        return back();
    }


    public function update(Request $request)
    {
        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->purchase_uom = $request->purchase_uom;
        $product->quantity_per_unit = $request->quantity_per_unit;
        $product->min_quantinty = $request->min_quantinty;
        $product->reorder_level = $request->reorder_level;
        $product->save();

        session()->flash("alert-success", "Product updated successfully!");
        return back();
    }


    public function destroy(Request $request)
    {
        try {
            Product::destroy($request->product_id);
            session()->flash("alert-danger", "Product deleted successfully!");
            return back();
        } catch (Exception $exception) {
            $product = Product::find($request->product_id);
            $product->status = 0;
            $product->save();
            session()->flash("alert-danger", "Product deleted successfully!");
            return back();
        }
    }



}
