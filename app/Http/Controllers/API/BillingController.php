<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Billing;
use App\Income;
use App\Product;
use App\ProductBilling;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Billing as BillingResource;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['data' => Billing::with('products')->orderBy('created_at', 'DESC')->get()];
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
            'total' => 'required|numeric',
            'products_quantity' => 'required|integer',
            'products' => 'required',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        } else {
            $billing = Billing::create([
                'client_document' => $request->client_document,
                'total' => $request->total,
                'products_quantity' => $request->products_quantity
            ]);

            foreach ($request->products as $product) {
                $product_billing = ProductBilling::create([
                    'product_id' => $product['id'],
                    'billing_id' => $billing->id,
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'discount' => $product['discount'],
                    'total' => ($product['quantity'] * $product['price']) * ((100 - $product['discount']) / 100)
                ]);

                $selected_product = Product::find($product['id']);
                if($selected_product) {
                    $selected_product->quantity = $selected_product->quantity - $product['quantity'];
                    $selected_product->save();
                }
            }

            $income = Income::create([
                'description' => 'Venta número ' . $billing->id,
                'income_value' => $billing->total
            ]);

            return ['data' => $billing];
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
        return ['data' => Billing::with('products')->find($id)];
    }

    public function last()
    {
        return ['data' => Billing::with('products')->orderBy('created_at', 'DESC')->first()];
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
        //
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
