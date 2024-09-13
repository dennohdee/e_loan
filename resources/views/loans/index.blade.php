@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Loans') }}</div>
                <div class="card-header">
                    <a href="{{ route('loans.create') }}" class="btn btn-primary float-end">New Loan</a>
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
                    <h5 class="card-title">Loan List</h5>
                    <div class="table-responsive table-striped">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Amount Applied</th>
                                    <th>Repayable Amount</th>
                                    <th>Amount Disbursed</th>
                                    <th>Status</th>
                                    <th>Amount Repaid</th>
                                    <th>Balance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($loans as $loan)
                            @php
                                $disbursed_amount = $loan->disbursements ? $loan->disbursements->sum('amount') : 0;
                                $repaid_amount = $loan->repayments ? $loan->repayments->sum('amount') : 0;
                                $balance = $loan->repayable_amount - $repaid_amount;
                            @endphp
                                <tr>
                                    <td>{{ $loan->customer->name }}</td>
                                    <td>{{ $loan->amount }}</td>
                                    <td>{{ $loan->repayable_amount }}</td>
                                    <td>{{ $disbursed_amount }}</td>
                                    <td>
                                        @switch($loan->status)
                                            @case('pending')
                                                <span class="badge rounded-pill bg-warning text-dark">{{ $loan->status }}</span>
                                                @break

                                            @case('approved')
                                                <span class="badge rounded-pill bg-success">{{ $loan->status }}</span>
                                                @break

                                            @case('disbursed')
                                                <span class="badge rounded-pill bg-primary">{{ $loan->status }}</span>
                                                @break
                                            
                                            @case('rejected')
                                                <span class="badge rounded-pill bg-danger">{{ $loan->status }}</span>
                                                @break

                                            @default
                                                <span class="badge rounded-pill bg-secondary">{{ $loan->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $repaid_amount }}</td>
                                    <td>{{ $balance }}</td>
                                    <td>
                                        <div class="dropdown" style="position: absolute;">
                                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="left: auto;">
                                                <li><a class="dropdown-item" href="{{ route('loans.approve', $loan->id) }}" onClick="return confirm('Sure to Approve Application?')">Approve</a></li>
                                                <li><a class="dropdown-item" href="{{ route('loans.reject', $loan->id) }}" onClick="return confirm('Sure to Reject Application?')">Reject</a></li>
                                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#disburseModal-{{ $loan->id }}">Disburse</a></li>
                                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#repayModal-{{ $loan->id }}">Repay</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <!-- disbursement modal -->
                                <div class="modal fade" id="disburseModal-{{ $loan->id }}" tabindex="-1" aria-labelledby="disburseModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="disburseModalLabel">Disburse Loan # {{ $loan->loanProduct->currency }} {{ $loan->amount }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h6 class="card-title"><b>Customer Name:</b> {{ $loan->customer->name }}</h6>
                                                <form action="{{ route('loans.disburse', $loan->id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="amount" class="form-label">Amount</label>
                                                        <input type="number" class="form-control" id="amount" name="amount" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="reference" class="form-label">Reference</label>
                                                        <input type="text" class="form-control" id="reference" name="reference" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="type" class="form-label">Type</label>
                                                        <select class="form-control" id="type" name="type" required>
                                                            <option value="">Select Type</option>
                                                            <option value="mpesa">MPESA</option>
                                                            <option value="cash">Cash</option>
                                                            <option value="bank_transfer">Bank Transfer</option>
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Disburse</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- repayment modal -->
                                <div class="modal fade" id="repayModal-{{ $loan->id }}" tabindex="-1" aria-labelledby="repayModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="repayModalLabel">Repay Loan | Balance {{ $loan->loanProduct->currency }} {{ $balance }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h6 class="card-title"><b>Customer Name:</b> {{ $loan->customer->name }}</h6>
                                                <form action="{{ route('loans.repay', $loan->id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="amount" class="form-label">Amount</label>
                                                        <input type="number" class="form-control" id="amount" name="amount" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="reference" class="form-label">Reference</label>
                                                        <input type="text" class="form-control" id="reference" name="reference" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="type" class="form-label">Type</label>
                                                        <select class="form-control" id="type" name="type" required>
                                                            <option value="">Select Type</option>
                                                            <option value="mpesa">MPESA</option>
                                                            <option value="cash">Cash</option>
                                                            <option value="bank_transfer">Bank Transfer</option>
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Repay</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
