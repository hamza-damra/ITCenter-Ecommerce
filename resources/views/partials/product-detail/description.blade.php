{{-- Product Description Component --}}
<div class="specifications-section" style="margin-top: 2rem;">
    <h2 class="section-title">{{ __('Product Description') }}</h2>
    <div style="color: #555; line-height: 1.8; font-size: 1rem;">
        {!! nl2br(e($product->description)) !!}
    </div>
</div>
