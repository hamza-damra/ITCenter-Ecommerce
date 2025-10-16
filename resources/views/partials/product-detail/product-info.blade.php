{{-- Product Information Component --}}
<div class="product-info">
    {{-- Category and Brand --}}
    <div class="product-category">
        {{ $product->category->name ?? __('Uncategorized') }}
        @if($product->brand)
            / {{ $product->brand->name }}
        @endif
    </div>

    {{-- Product Title --}}
    <h1 class="product-title">{{ $product->name }}</h1>

    {{-- Product Rating --}}
    @include('partials.product-detail.rating', ['product' => $product])

    {{-- Product Price --}}
    @include('partials.product-detail.price', ['product' => $product])

    {{-- Stock Status --}}
    @include('partials.product-detail.stock-status', ['product' => $product])

    {{-- Short Description --}}
    <p class="product-description">
        {{ $product->short_description ?? $product->description }}
    </p>

    {{-- Product Features --}}
    @include('partials.product-detail.features')

    {{-- Quantity Selector --}}
    @include('partials.product-detail.quantity-selector', ['product' => $product])

    {{-- Action Buttons --}}
    @include('partials.product-detail.action-buttons', ['product' => $product])
</div>
