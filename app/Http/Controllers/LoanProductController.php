<?php

namespace App\Http\Controllers;

use App\Models\LoanProduct;
use Illuminate\Http\Request;
use DB;
use Log;

class LoanProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loanProducts = LoanProduct::with('loans')->get();
        return view('loan_products.index', compact('loanProducts'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LoanProduct  $loanProduct
     * @return \Illuminate\Http\Response
     */
    public function show(LoanProduct $loanProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoanProduct  $loanProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(LoanProduct $loanProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LoanProduct  $loanProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoanProduct $loanProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoanProduct  $loanProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoanProduct $loanProduct)
    {
        //
    }
}
