{{-- Product Specifications Component - Enhanced --}}
@php
    $formattedSpecs = $product->formattedSpecifications ?? [];
    $hasLegacySpecs = $product->specifications && is_array($product->specifications) && count($product->specifications) > 0;
    $hasNewSpecs = count($formattedSpecs) > 0;
@endphp

<div class="specifications-section">
    <h2 class="section-title">
        <i class="fas fa-microchip"></i>
        {{ __('messages.technical_specifications') }}
    </h2>
    <div class="specs-grid">
        {{-- New spec template values --}}
        @if($hasNewSpecs)
            @foreach($formattedSpecs as $spec)
                @if(!empty($spec['value']))
                    <div class="spec-item">
                        <span class="spec-label">
                            <i class="fas fa-check-circle"></i>
                            {{ $spec['label'] }}:
                        </span>
                        <span class="spec-value">{{ $spec['value'] }}</span>
                    </div>
                @endif
            @endforeach
        {{-- Legacy JSON specifications fallback --}}
        @elseif($hasLegacySpecs)
            @foreach($product->specifications as $key => $value)
                @if(!empty($value))
                    <div class="spec-item">
                        <span class="spec-label">
                            <i class="fas fa-check-circle"></i>
                            {{ ucfirst(str_replace('_', ' ', $key)) }}:
                        </span>
                        <span class="spec-value">{{ $value }}</span>
                    </div>
                @endif
            @endforeach
        @endif
        
        {{-- Always show SKU --}}
        <div class="spec-item">
            <span class="spec-label">
                <i class="fas fa-barcode"></i>
                {{ __('messages.sku') ?? 'SKU' }}:
            </span>
            <span class="spec-value">{{ $product->sku }}</span>
        </div>
        
        {{-- Show weight if available --}}
        @if($product->weight)
            <div class="spec-item">
                <span class="spec-label">
                    <i class="fas fa-weight-hanging"></i>
                    {{ __('messages.weight') ?? 'Weight' }}:
                </span>
                <span class="spec-value">{{ $product->weight }} <span class="spec-unit">kg</span></span>
            </div>
        @endif
        
        {{-- Show warranty if available --}}
        @if($product->warranty)
            <div class="spec-item">
                <span class="spec-label">
                    <i class="fas fa-shield-alt"></i>
                    {{ __('messages.warranty') }}:
                </span>
                <span class="spec-value">{{ $product->warranty }}</span>
            </div>
        @endif
        
        {{-- Show dimensions if available --}}
        @if($product->length && $product->width && $product->height)
            <div class="spec-item">
                <span class="spec-label">
                    <i class="fas fa-cube"></i>
                    {{ __('messages.dimensions') ?? 'Dimensions' }}:
                </span>
                <span class="spec-value">{{ $product->length }} × {{ $product->width }} × {{ $product->height }} <span class="spec-unit">cm</span></span>
            </div>
        @endif
    </div>
</div>
