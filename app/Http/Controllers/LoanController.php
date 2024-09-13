<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Customer;
use App\Models\LoanProduct;
use App\Models\LoanRepayment;
use App\Models\LoanDisbursement;
use Illuminate\Http\Request;
use DB;
use Log;

class LoanController extends Controller
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
        $loans = Loan::with(['customer', 'disbursements', 'repayments', 'loanProduct'])->get();
        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::whereHas('loanLimit')->get();
        $loanProducts = LoanProduct::all();
        return view('loans.create', compact('customers', 'loanProducts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate input
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'loan_product_id' => 'required|exists:loan_products,id',
            'amount' => 'required|numeric|min:1000',
            'due_date' => 'required|date|after:today',
        ],[
            'customer_id.exists' => 'Customer not found',
            'loan_product_id.exists' => 'Loan product not found'
        ]);

        try {
            DB::beginTransaction();
            //get loan product
            $loanProduct = LoanProduct::findOrFail($request->loan_product_id);
            //get customer
            $customer = Customer::findOrFail($request->customer_id);
            //check product limits
            $productMaxAmount = $loanProduct->maximum_amount;
            $productMinAmount = $loanProduct->minimum_amount;
            // throw error if not within loan product limit
            if($request->amount < $productMinAmount || $request->amount > $productMaxAmount) {
                return back()->withErrors(['error' => 'Loan amount is not within product limits [Min: '.$productMinAmount.', Max: '.$productMaxAmount.']']);
            }
            //throw error if not within customer limit
            if ($customer->loanLimit->limit_amount < $request->amount) {
                return back()->withErrors(['error' => 'Customer loan limit is lower than requested amount [Limit: '.$customer->loanLimit->limit_amount.']']);
            }

            //calculate amount to be repaid
            $amountRepayable = $request->amount + ($request->amount * $loanProduct->interest_rate / 100);
            //record new loan
            $loan = new Loan();
            $loan->customer_id = $customer->id;
            $loan->loan_product_id = $loanProduct->id;
            $loan->amount = $request->amount;
            $loan->repayable_amount = $amountRepayable;
            $loan->due_date = $request->due_date;
            $loan->save();

            DB::commit();
            return redirect()->route('loans')->with('success', 'Loan added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->withErrors(['error' => 'Oops! An error occured, please try again shortly.']);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        return view('loans.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, Loan $loan)
    {
        try {
            DB::beginTransaction();
            $loan->status = 'approved';
            $loan->save();

            DB::commit();
            return redirect()->route('loans')->with('success', 'Loan approved successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->withErrors(['error' => 'Oops! An error occured, please try again shortly.']);
        }
    }

    public function reject(Request $request, Loan $loan)
    {
        try {
            DB::beginTransaction();
            $loan->status = 'rejected';
            $loan->save();

            DB::commit();
            return redirect()->route('loans')->with('success', 'Loan Rejected successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->withErrors(['error' => 'Oops! An error occured, please try again shortly.']);
        }
    }

    public function disburse(Request $request, Loan $loan)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'type' => 'required|string',
            'reference' => 'required|string'
        ],[
            'loan_id' => 'Loan does not exist'
        ]);
        try {
            DB::beginTransaction();
            //update status
            $loan->status = 'disbursed';
            $loan->save();
            //record disbursement
            $disbursement = new LoanDisbursement();
            $disbursement->loan_id = $loan['id'];
            $disbursement->amount = $request->amount;
            $disbursement->transaction_type = $request->type;
            $disbursement->reference = $request->reference;
            $disbursement->status = 'successful';
            $disbursement->save();
            DB::commit();
            return redirect()->route('loans')->with('success', 'Loan Disbursed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->withErrors(['error' => 'Oops! An error occured, please try again shortly.']);
        }
    }

    public function repay(Request $request, Loan $loan)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'type' => 'required|string',
            'reference' => 'required|string'
        ],[
            'loan_id' => 'Loan does not exist'
        ]);
        try {
            DB::beginTransaction();
            //update status
            $loan->status = 'disbursed';
            $loan->save();
            //record repayment
            $repayment = new LoanRepayment();
            $repayment->loan_id = $loan['id'];
            $repayment->amount = $request->amount;
            $repayment->transaction_type = $request->type;
            $repayment->reference = $request->reference;
            $repayment->status = 'successful';
            $repayment->save();
            DB::commit();
            return redirect()->route('loans')->with('success', 'Loan Repaid successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->withErrors(['error' => 'Oops! An error occured, please try again shortly.']);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        //
    }
}
