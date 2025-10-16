{{-- Product Specifications Component --}}
<div class="specifications-section">
    <h2 class="section-title">{{ __('Technical Specifications') }}</h2>
    <div class="specs-grid">
        @if($product->specifications && is_array($product->specifications))
            {{-- Display custom specifications from JSON --}}
            @foreach($product->specifications as $key => $value)
                <div class="spec-item">
                    <span class="spec-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                    <span class="spec-value">{{ $value }}</span>
                </div>
            @endforeach
        @else
            {{-- Display default specifications --}}
            <div class="spec-item">
                <span class="spec-label">{{ __('SKU') }}:</span>
                <span class="spec-value">{{ $product->sku }}</span>
            </div>
            
            @if($product->weight)
                <div class="spec-item">
                    <span class="spec-label">{{ __('Weight') }}:</span>
                    <span class="spec-value">{{ $product->weight }} {{ __('kg') }}</span>
                </div>
            @endif
            
            @if($product->warranty)
                <div class="spec-item">
                    <span class="spec-label">{{ __('Warranty') }}:</span>
                    <span class="spec-value">{{ $product->warranty }}</span>
                </div>
            @endif
            
            @if($product->length && $product->width && $product->height)
                <div class="spec-item">
                    <span class="spec-label">{{ __('Dimensions') }}:</span>
                    <span class="spec-value">
                        {{ $product->length }} x {{ $product->width }} x {{ $product->height }} {{ __('cm') }}
                    </span>
                </div>
            @endif
        @endif
    </div>
</div>
