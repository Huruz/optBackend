<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduct;
use App\Http\Requests\UpdateProduct;
use \Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = Product::all();

        if(!$products->isEmpty()){
            return response()->json($products,200);
        }
        else{
            return response()->json(NULL,200);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\RequestsStoreProduct  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProduct $request)
    {
        $validated = $request->validated();

        // Create a new product
        $product = Product::create($request->all());

        // Return a response with a product json
        // representation and a 201 status code
        return response()->json($product,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $product = Product::find($id);
        if (!is_null($product)){
            return response()->json($product,200);
        }
        else{
            $error = ['errors' => ['code' => 'Error-2', 'title' => 'ID does not exist']];
            return response()->json($error,404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProduct $request, $id)
    {
        $validated = $request->validated();
        //

        $product = Product::find($id);
        if (!is_null($product)){
            $product->update($request->all());
            return response()->json($product,200);
        }
        else{
            $error = ['errors' => ['code' => 'Error-2', 'title' => 'ID does not exist']];
            return response()->json($error,404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $product = Product::find($id);
        //$product = Product::where('id',$id)->delete();

        if (!is_null($product)){
            $product->delete();
            return response()->json(NULL,204);
        }
        else{
            $error = ['errors' => ['code' => 'Error-2', 'title' => 'ID does not exist']];
            return response()->json($error,404);
        }
    }
}
