@extends('layouts.owner')
@section('title', 'Help & Support')
@section('content')

<div class="container mx-auto max-w-4xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Help & Support</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Welcome to the Help Center</h2>
        <p class="text-gray-600 mb-6">
            Here you can find answers to common questions and get support for using the Investment & Trading Management System.
        </p>

        <h3 class="text-xl font-bold text-gray-800 mb-3">Frequently Asked Questions</h3>
        <div class="space-y-4 mb-6">
            <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded">
                <h4 class="font-bold text-lg text-blue-800">How do I approve an expense?</h4>
                <p class="text-blue-600">Navigate to the `Requests` section in the sidebar. You will see a list of pending expense requests. Click `Approve` to finalize the expense.</p>
            </div>
            <div class="border-l-4 border-purple-500 bg-purple-50 p-4 rounded">
                <h4 class="font-bold text-lg text-purple-800">How do I view financial reports?</h4>
                <p class="text-purple-600">Go to the `Reports & Analytics` section. You can select from various reports like the P&L statement, aging reports, and more.</p>
            </div>
        </div>

        <h3 class="text-xl font-bold text-gray-800 mb-3">Contact Support</h3>
        <p class="text-gray-600">
            If you need further assistance, please contact the technical support team.
        </p>
        <ul class="list-disc list-inside mt-4 text-gray-600">
            <li>Email: support@company.com</li>
            <li>Phone: +1 (555) 123-4567</li>
        </ul>
    </div>
</div>

@endsection