{{-- Product Image Gallery Component --}}
<div class="product-images">
    {{-- Main Image --}}
    <div class="main-image">
        @php
            $mainImageUrl = $product->main_image 
                ? (filter_var($product->main_image, FILTER_VALIDATE_URL) 
                    ? $product->main_image 
                    : asset('storage/' . $product->main_image))
                : 'https://via.placeholder.com/800x800/f5f5f5/666666?text=' . urlencode($product->name);
        @endphp
        <img 
            src="{{ $mainImageUrl }}" 
            alt="{{ $product->name }}" 
            id="mainImage" 
            onerror="this.src='https://via.placeholder.com/800x800/f5f5f5/666666?text=No+Image'"
        >
    </div>

    {{-- Thumbnail Images --}}
    <div class="thumbnail-images">
        @if($product->images->count() > 0)
            @foreach($product->images->take(4) as $index => $image)
                <div class="thumbnail {{ $index === 0 ? 'active' : '' }}">
                    @php
                        $thumbnailUrl = $image->image_path 
                            ? (filter_var($image->image_path, FILTER_VALIDATE_URL) 
                                ? $image->image_path 
                                : asset('storage/' . $image->image_path))
                            : 'https://via.placeholder.com/200x200/f5f5f5/666666?text=Image+' . ($index + 1);
                    @endphp
                    <img 
                        src="{{ $thumbnailUrl }}" 
                        alt="{{ $product->name }}" 
                        onclick="changeImage(this)" 
                        onerror="this.src='https://via.placeholder.com/200x200/f5f5f5/666666?text=No+Image'"
                    >
                </div>
            @endforeach
        @else
            <div class="thumbnail active">
                <img 
                    src="{{ $mainImageUrl }}" 
                    alt="{{ $product->name }}" 
                    onclick="changeImage(this)" 
                    onerror="this.src='https://via.placeholder.com/200x200/f5f5f5/666666?text=No+Image'"
                >
            </div>
        @endif
    </div>
</div>
