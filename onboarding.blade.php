@extends('layouts.app')

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        .step-container {
            display: none;
        }
        .step-container.active {
            display: block;
        }
        .step-indicator {
            width: 50px;
            height: 50px;
            background-color: #e0e0e0;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .step-indicator.active {
            transform: scale(1.1);
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
        }
        .step-indicator-text {
            font-weight: 600;
            margin-top: 8px;
        }
        .bg-gradient-to-r {
            background-image: linear-gradient(to right, var(--tw-gradient-stops));
        }
        .from-blue-600 {
            --tw-gradient-from: #2563eb;
            --tw-gradient-to: rgba(37, 99, 235, 0);
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
        }
        .to-indigo-600 {
            --tw-gradient-to: #4f46e5;
        }
        .from-green-500 {
            --tw-gradient-from: #22c55e;
            --tw-gradient-to: rgba(34, 197, 94, 0);
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
        }
        .to-emerald-600 {
            --tw-gradient-to: #059669;
        }
    </style>
@endpush

@section('title', 'Complete Your Profile')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 text-center">
                    <h2 class="mb-0 fw-bold fs-3">Investor Profile Setup</h2>
                    <p class="mb-0 text-white-50">Complete these steps to activate your account</p>
                </div>
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div class="text-center">
                            <div id="step1-indicator" class="step-indicator bg-primary text-white">
                                <i class="bi bi-lock-fill"></i>
                            </div>
                            <div class="step-indicator-text text-muted small">Account Security</div>
                        </div>
                        <hr class="flex-grow-1 mx-3 border-2 border-primary">
                        <div class="text-center">
                            <div id="step2-indicator" class="step-indicator bg-secondary text-white">
                                <i class="bi bi-bank"></i>
                            </div>
                            <div class="step-indicator-text text-muted small">Bank Details</div>
                        </div>
                        <hr class="flex-grow-1 mx-3 border-2 border-secondary">
                        <div class="text-center">
                            <div id="step3-indicator" class="step-indicator bg-secondary text-white">
                                <i class="bi bi-journal-check"></i>
                            </div>
                            <div class="step-indicator-text text-muted small">Final Declaration</div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div id="step1" class="step-container {{ $step == 1 ? 'active' : '' }}">
                        <form method="POST" action="{{ route('investor.onboarding.complete.step1') }}">
                            @csrf
                            <h4 class="mb-4 text-center fw-bold">Step 1: Secure Your Account</h4>
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" name="password" id="password" class="form-control" required />
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required />
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">Next</button>
                            </div>
                        </form>
                    </div>

                    <div id="step2" class="step-container {{ $step == 2 ? 'active' : '' }}">
                        <form method="POST" action="{{ route('investor.onboarding.complete.step2') }}">
                            @csrf
                            <h4 class="mb-4 text-center fw-bold">Step 2: Provide Bank Details</h4>
                            <div class="mb-3">
                                <label for="bank_name" class="form-label">Bank Name</label>
                                <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ $onboardingData['bank_name'] ?? old('bank_name') }}" required />
                            </div>
                            <div class="mb-3">
                                <label for="bank_account_number" class="form-label">Account Number</label>
                                <input type="text" name="bank_account_number" id="bank_account_number" class="form-control" value="{{ $onboardingData['bank_account_number'] ?? old('bank_account_number') }}" required />
                            </div>
                            <div class="mb-4">
                                <label for="account_holder_name" class="form-label">Account Holder Name</label>
                                <input type="text" name="account_holder_name" id="account_holder_name" class="form-control" value="{{ $onboardingData['account_holder_name'] ?? old('account_holder_name') }}" required />
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('investor.onboarding.show', ['step' => 1]) }}" class="btn btn-secondary fw-bold">Back</a>
                                <button type="submit" class="btn btn-primary fw-bold">Next</button>
                            </div>
                        </form>
                    </div>
                    
                    <div id="step3" class="step-container {{ $step == 3 ? 'active' : '' }}">
                        <form method="POST" action="{{ route('investor.onboarding.complete.step3') }}">
                            @csrf
                            <h4 class="mb-4 text-center fw-bold">Step 3: Final Declaration</h4>
                            <div class="alert alert-info rounded-3">
                                <p class="mb-0">
                                    By checking this box, I declare that all the information provided is accurate and that I agree to all the terms and conditions of the investment partnership.
                                </p>
                            </div>
                            <div class="form-check mb-4">
                                <input type="checkbox" id="declaration_signed" name="declaration_signed" class="form-check-input" required />
                                <label for="declaration_signed" class="form-check-label text-dark fw-bold">
                                    I acknowledge and agree to the terms mentioned above.
                                </label>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('investor.onboarding.show', ['step' => 2]) }}" class="btn btn-secondary fw-bold">Back</a>
                                <button type="submit" class="btn bg-gradient-to-r from-green-500 to-emerald-600 text-white btn-lg fw-bold">Complete Profile</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stepIndicators = {
            1: document.getElementById('step1-indicator'),
            2: document.getElementById('step2-indicator'),
            3: document.getElementById('step3-indicator'),
        };
        const currentStep = {{ $step }};
        
        for (let i = 1; i <= currentStep; i++) {
            if (i < currentStep) {
                stepIndicators[i].classList.remove('bg-secondary');
                stepIndicators[i].classList.remove('bg-primary');
                stepIndicators[i].classList.add('bg-success');
            } else {
                stepIndicators[i].classList.add('active');
                stepIndicators[i].classList.remove('bg-secondary');
                stepIndicators[i].classList.add('bg-primary');
            }
        }
        
        // Update future step indicators
        for (let i = currentStep + 1; i <= 3; i++) {
            stepIndicators[i].classList.remove('active');
            stepIndicators[i].classList.remove('bg-primary');
            stepIndicators[i].classList.remove('bg-success');
            stepIndicators[i].classList.add('bg-secondary');
        }
    });
</script>
@endsection
