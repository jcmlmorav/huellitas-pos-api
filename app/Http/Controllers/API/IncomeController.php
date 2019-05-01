<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Income;
use App\Billing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['data' => Income::orderBy('created_at', 'DESC')->take(15)->get()];
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
            'description' => 'required|string|max:100',
            'income_value' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        } else {
            $income = Income::create([
                'description' => $request->description,
                'income_value' => $request->income_value
            ]);

            return ['data' => $income];
        }
    }

    public function resume()
    {
        return ['data' => Billing::sum('total')];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
