@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Customers') }}</div>
                <div class="card-header">
                    <a href="{{ route('customers.create') }}" class="btn btn-primary float-end">Add Customer</a>
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
                    <h5 class="card-title">Customer List</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Loan Limit</th>
                            <th>Loans Taken</th>
                            <th width="280px">Action</th>
                        </tr>
                        @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->loanLimit ? $customer->loanLimit->limit_amount : 'N/A' }}</td>
                            <td>{{ $customer->loans->count() }}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ route('customers.edit',$customer->id) }}">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
