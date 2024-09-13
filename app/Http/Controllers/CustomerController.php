<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use DB;
use Log;
use Auth;
class CustomerController extends Controller
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
        //return customer list
        $customers = Customer::withCount('loans')->with('loanLimit')->get();
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:65',
            'email'=>'required|email|unique:customers',
            'phone_number'=>'required|string|max:14',
            'id_number'=>'required|string|max:10',
            'dob' => 'required|date|before:today',
            'loan_limit'=>'nullable|numeric'
        ]);

        try {
            DB::beginTransaction();
            $customer = new Customer();
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->dob = $request->dob;
            $customer->phoneNumber = $request->phone_number;
            $customer->idNumber = $request->id_number;
            $customer->address = $request->address;
            $customer->save();

            $customer->loanLimit()->create(['limit_amount'=> $request->loan_limit ?? 0]);
            DB::commit();
            return redirect()->route('customers')->with('success', 'Customer added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->withErrors(['error' => 'Oops! An error occured, please try again shortly.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        $customer->load('loanLimit');
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:65',
            'email' => 'required|string|email|max:65|unique:customers,email,' . $customer->id,
            'dob' => 'required|date',
            'phone_number' => 'required|string|max:14|unique:customers,idNumber,' . $customer->id,
            'id_number' => 'required|string|max:10|unique:customers,idNumber,' . $customer->id,
            'address' => 'required|string|max:65',
        ]);
        try {
            DB::beginTransaction();
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->dob = $request->dob;
            $customer->phoneNumber = $request->phone_number;
            $customer->idNumber = $request->id_number;
            $customer->address = $request->address;
            $customer->save();

            if ($request->loan_limit) {
                $customer->loanLimit()->update(['limit_amount' => $request->loan_limit]);
            }
            DB::commit();
            return redirect()->route('customers')->with('success', 'Customer updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->withErrors(['error' => 'Oops! An error occured, please try again shortly.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
