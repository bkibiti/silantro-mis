<?php

namespace App\Http\Controllers;

use App\PriceCategory;
use Exception;
use Illuminate\Http\Request;

class PriceCategoryController extends Controller
{
    public function index()
    {

        $price_category = PriceCategory::orderBy('id', 'ASC')->get();
        return view('masters.price_category.index')->with('price_category', $price_category);
    }



    public function update(Request $request)
    {

        $price_category = PriceCategory::find($request->price_category_id);
        $price_category->description = $request->description;
        $price_category->save();

        session()->flash("alert-success", "Price category updated successfully!");
        return back();

    }


}
