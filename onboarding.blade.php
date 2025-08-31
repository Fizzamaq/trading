@extends('layouts.app')

@section('title', 'Complete Your Profile')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-person-check me-2"></i>Complete Your Investor Profile</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('investor.onboarding.complete') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password *</label>
                            <input type="password" name="password" id="password" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Bank Name *</label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ old('bank_name') }}" required />
                        </div>
                        <div class="mb-3">
                            <label for="bank_account_number" class="form-label">Account Number *</label>
                            <input type="text" name="bank_account_number" id="bank_account_number" class="form-control" value="{{ old('bank_account_number') }}" required />
                        </div>
                        <div class="mb-3">
                            <label for="account_holder_name" class="form-label">Account Holder Name *</label>
                            <input type="text" name="account_holder_name" id="account_holder_name" class="form-control" value="{{ old('account_holder_name') }}" required />
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" id="declaration_signed" name="declaration_signed" class="form-check-input" required />
                            <label for="declaration_signed" class="form-check-label">
                                I acknowledge and agree to all the terms mentioned above *
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Complete Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
