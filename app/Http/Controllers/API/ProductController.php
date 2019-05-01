<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product as ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['data' => Product::all()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barcode' => 'required|unique:products|max:100',
            'description' => 'required|max:40',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'discount' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        } else {
            $product = Product::create([
                'barcode' => $request->barcode,
                'description' => $request->description,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'discount' => $request->discount
            ]);

            return ['data' => $product];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::where('barcode', $id)->first();
        if($product) {
            return ['data' => $product];
        } else {
            $product = Product::where('description', 'like', '%'.$id.'%')->get();
            if($product->count() > 0) {
                return ['data' => $product];
            } else {
                return ['data' => Product::where('price', $id)->get()];
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if ($product) {
            $validator = Validator::make($request->all(), [
                'description' => 'required|max:40',
                'quantity' => 'required|integer',
                'price' => 'required|numeric',
                'discount' => 'required|numeric'
            ]);
    
            if ($validator->fails()) {
                return ['error' => $validator->errors()];
            } else {    
                $product->description = $request->description;
                $product->quantity = $request->quantity;
                $product->price = $request->price;
                $product->discount = $request->discount;
    
                $product->save();
    
                return ['data' => $product];
            }
        } else {
            return ['error' => [
                'product' => ['El producto que intenta actualizar no existe']
            ]];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
