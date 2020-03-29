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
        return ['data' => Billing::with('products')->orderBy('created_at', 'DESC')->take(100)->get()];
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
                'products_quantity' => $request->products_quantity,
                'coupon' => $request->coupon,
                'coupon_discount' => $request->coupon_discount,
                'money' => $request->money,
                'change' => $request->change
            ]);

            foreach ($request->products as $product) {
                $query = Product::where('barcode', $product['barcode'])->first();

                $product_billing = ProductBilling::create([
                    'product_id' => $query->id,
                    'billing_id' => $billing->id,
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'discount' => $product['discount'],
                    'total' => ($product['quantity'] * $product['price']) * ((100 - $product['discount']) / 100)
                ]);

                $selected_product = Product::find($query->id);
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
        $billing = Billing::find($id);

        if ($billing) {
            $validator = Validator::make($request->all(), [
                'total' => 'required|numeric',
                'products_quantity' => 'required|integer',
                'products' => 'required',
            ]);

            if ($validator->fails()) {
                return ['error' => $validator->errors()];
            } else {
                $billing->client_document = $request->client_document;
                $billing->total = $request->total;
                $billing->products_quantity = $request->products_quantity;
                $billing->coupon = $request->coupon;
                $billing->coupon_discount = $request->coupon_discount;
                $billing->money = $request->money;
                $billing->change = $request->change;

                $billing->save();

                foreach ($billing->products as $product) {
                    $query = Product::where('barcode', $product['barcode'])->first();
                    $selected_product = Product::find($query->id);
                    if($selected_product) {
                        $selected_product->quantity = $selected_product->quantity + $product['quantity'];
                        $selected_product->save();
                    }
                }

                $billing->products()->detach();

                foreach ($request->products as $product) {
                    $query = Product::where('barcode', $product['barcode'])->first();
    
                    $product_billing = ProductBilling::create([
                        'product_id' => $query->id,
                        'billing_id' => $billing->id,
                        'quantity' => $product['quantity'],
                        'price' => $product['price'],
                        'discount' => $product['discount'],
                        'total' => ($product['quantity'] * $product['price']) * ((100 - $product['discount']) / 100)
                    ]);
    
                    $selected_product = Product::find($query->id);
                    if($selected_product) {
                        $selected_product->quantity = $selected_product->quantity - $product['quantity'];
                        $selected_product->save();
                    }
                }

                return ['data' => $billing];
            }
        } else {
            return ['error' => [
                'billing' => ['La factura que intenta actualizar no existe']
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
