@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Customers</h5>
                                    <p class="card-text">Number of customers: {{ $customers }}</p>
                                    <a href="" class="btn btn-sm btn-primary">View</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Loans</h5>
                                    <p class="card-text">Number of loans: {{ $loans }}</p>
                                    <a href="" class="btn btn-sm btn-primary">View</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Loan Products</h5>
                                    <p class="card-text">Number of loan products: {{ $loanProductsCount }}</p>
                                    <a href="" class="btn btn-sm btn-primary">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <!-- loan products list -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Loan Products</h5>
                    <div class="table-responsive table-striped">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Currency</th>
                                    <th>Amount Range</th>
                                    <th>Interest(%)</th>
                                    <th>Active Loans</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($loanProducts as $loanProduct)
                                <tr>
                                    <td>{{ $loanProduct->name }}</td>
                                    <td>{{ $loanProduct->code }}</td>
                                    <td>{{ $loanProduct->currency }}</td>
                                    <td>{{ $loanProduct->minimum_amount }} - {{ $loanProduct->maximum_amount }}</td>
                                    <td>{{ $loanProduct->interest_rate }}%</td>
                                    <td>{{ $loanProduct->loans->count() }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
