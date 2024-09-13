@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- loan products list -->                 
            <div class="card">
            <div class="card-header">{{ __('Loan Products') }}</div>
                <div class="card-header">
                    <a href="{{ route('loan-products.create') }}" class="btn btn-primary float-end">New Loan Product</a>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
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
                                    <td>
                                        <a class="btn btn-primary" href="#">Edit</a>
                                    </td>
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