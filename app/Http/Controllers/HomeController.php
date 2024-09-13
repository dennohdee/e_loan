<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Loan;
use App\Models\LoanProduct;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $customers = Customer::count();
        $loans = Loan::count();
        $loanProducts = LoanProduct::with('loans')->get();
        
        $loanProductsCount = LoanProduct::count();

        return view('home', compact('customers', 'loans', 'loanProducts', 'loanProductsCount'));
    }
}
