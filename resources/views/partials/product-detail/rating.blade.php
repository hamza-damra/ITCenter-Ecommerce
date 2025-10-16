{{-- Product Rating Component --}}
<div class="product-rating">
    <div class="stars">
        @for($i = 1; $i <= 5; $i++)
            @if($i <= floor($product->avg_rating))
                <i class="fas fa-star"></i>
            @elseif($i - $product->avg_rating < 1)
                <i class="fas fa-star-half-alt"></i>
            @else
                <i class="far fa-star"></i>
            @endif
        @endfor
    </div>
    <span class="rating-text">
        {{ number_format($product->avg_rating, 1) }} 
        ({{ $product->reviews_count }} {{ __('reviews') }})
    </span>
</div>
