{{-- Related Products Component --}}
<div class="related-products">
    <div class="container">
        <h2 class="related-title">{{ __('Related Products') }}</h2>
        <div class="products-grid">
            @foreach($relatedProducts as $relatedProduct)
                <a href="{{ route('product.detail', $relatedProduct->slug) }}" style="text-decoration: none; color: inherit;">
                    <div class="product-card">
                        <div class="product-card-image">
                            @php
                                $relatedImageUrl = $relatedProduct->main_image 
                                    ? (filter_var($relatedProduct->main_image, FILTER_VALIDATE_URL) 
                                        ? $relatedProduct->main_image 
                                        : asset('storage/' . $relatedProduct->main_image))
                                    : 'https://via.placeholder.com/300x200/f5f5f5/666666?text=' . urlencode($relatedProduct->name);
                            @endphp
                            <img 
                                src="{{ $relatedImageUrl }}" 
                                alt="{{ $relatedProduct->name }}" 
                                onerror="this.src='https://via.placeholder.com/300x200/f5f5f5/666666?text=No+Image'"
                            >
                        </div>
                        <div class="product-card-content">
                            <h3 class="product-card-title">{{ $relatedProduct->name }}</h3>
                            <div class="product-card-price">${{ number_format($relatedProduct->final_price, 2) }}</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
