<?php

namespace App\Http\Controllers\API;

use App\Income;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalesController extends Controller
{
    public function index()
    {
        return ['data' => Income::whereDate('created_at', Carbon::today())->orderBy('created_at', 'DESC')->get()];
    }

    public function resume()
    {
        return ['data' => Income::whereDate('created_at', Carbon::today())->sum('income_value')];
    }
}
