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
    
    public function arrayQuestion() {
        //initialize array
        $array = [];
        //generate 20 records
        for ($i = 0; $i < 20; $i++) {
            $new_value = rand(99, 200);
            array_push($array, $new_value);
            $i+1;
        }
        echo json_encode($array);
        echo ">>>>>>>>>>>>>>>>>>>>>>>>";

        $batchSize = 5;
        $array_length = count($array);
        $batchCount = ceil($array_length / $batchSize);
        // loop foor batches
        for ($i = 0; $i < $batchCount; $i++) {
            $start = $i * $batchSize;
            //return the smallest end value to avoid exception - either length if smallest else batchsize 
            $end = min($start + $batchSize, $array_length);
            //set batch No
            $batchNo = $i+1;
            echo " Batch $batchNo:\n";
            //loop for values in each batch
            for ($j = $start; $j < $end; $j++) {
                //print index and value of it
                print "{  $j: $array[$j] }\n";
            }
            echo "\n";
        }

        return '=======END===========';
    }
}
