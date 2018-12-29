<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Expense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['data' => Expense::orderBy('created_at', 'DESC')->take(15)->get()];
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
            'expense_value' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        } else {
            $expense = Expense::create([
                'description' => $request->description,
                'expense_value' => $request->expense_value
            ]);

            return ['data' => $expense];
        }
    }

    public function resume()
    {
        return ['data' => Expense::sum('expense_value')];
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
