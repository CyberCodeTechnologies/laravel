@extends('layouts.app')

@section('title', __('messages.payment_instructions') . ' - Panchi Gallery')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-serif font-bold mb-8">{{ __('messages.payment_instructions') }}</h1>
        
        <!-- Payment Method Info -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center text-white font-bold text-xl">
                    {{ substr($paymentMethod->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl font-semibold">{{ $paymentMethod->name }}</h2>
                    <p class="text-gray-600">Order #{{ $order->order_number }}</p>
                </div>
            </div>
            
            <div class="border-t pt-4 mt-4">
                <p class="text-sm text-gray-500 mb-2">{{ __('messages.total') }}</p>
                <p class="text-3xl font-bold">{{ \App\Helpers\CurrencyHelper::format($order->total_amount) }}</p>
            </div>
        </div>

        <!-- Instructions -->
        <div class="mb-8">
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            {{ __('messages.please_transfer_exact_amount', ['amount' => number_format($order->total_amount)]) }} {{ $order->currency }}
                        </p>
                    </div>
                </div>
            </div>

            @if($paymentMethod->qr_code)
                <div class="flex flex-col items-center justify-center mb-8 p-6 bg-white border-2 border-dashed border-gray-200 rounded-xl">
                    <p class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">{{ __('messages.scan_to_pay') }}</p>
                    <img src="{{ asset('storage/' . $paymentMethod->qr_code) }}" alt="MMQR Code" class="w-48 h-48 object-contain shadow-lg rounded-lg border p-2">
                    <p class="text-xs text-gray-500 mt-4">{{ __('messages.scan_with_mobile_app', ['method' => $paymentMethod->name]) }}</p>
                </div>
            @endif

            <div class="prose prose-sm max-w-none mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.payment_instructions') }}</h3>
                <div class="bg-gray-50 p-4 rounded-lg border whitespace-pre-wrap text-gray-700 leading-relaxed">{{ $paymentMethod->instructions }}</div>
            </div>
        </div>

        <!-- Upload Form -->
        <form action="{{ route('payment.upload', $order) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('messages.upload_payment_proof') }}
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-black transition-colors">
                    <input type="file" name="screenshot" id="screenshot" accept="image/*" required
                        class="hidden" onchange="previewImage(this)">
                    <label for="screenshot" class="cursor-pointer block">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">{{ __('messages.upload_screenshot') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ __('messages.supported_formats') }}</p>
                        <p class="text-xs text-gray-500">{{ __('messages.max_file_size') }}</p>
                    </label>
                    <div id="preview" class="mt-4 hidden">
                        <img id="preview-image" class="max-h-48 mx-auto rounded-lg" />
                    </div>
                    @error('screenshot')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="transaction_reference" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('messages.transaction_reference') }} ({{ __('messages.optional') }})
                </label>
                <input type="text" name="transaction_reference" id="transaction_reference"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                    placeholder="e.g., TRX123456789">
                @error('transaction_reference')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('messages.notes') }} ({{ __('messages.optional') }})
                </label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                    placeholder="Any additional information about your payment..."></textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('checkout.index') }}" class="text-gray-600 hover:text-black">
                    {{ __('messages.back') }}
                </a>
                <button type="submit" class="btn-luxury">
                    {{ __('messages.upload_payment_proof') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    const previewImg = document.getElementById('preview-image');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
