@extends('layouts.app')

@section('content')
<div class="container" style="padding: 40px;">
    <h1>🧪 Test Home Sections</h1>
    
    <div style="background: #f0f0f0; padding: 20px; margin: 20px 0; border-radius: 8px;">
        <h2>New Products ({{ $newProducts->count() }})</h2>
        @if($newProducts->count() > 0)
            <ul>
                @foreach($newProducts as $product)
                    <li>{{ $product->name }} - ₪{{ $product->price }}</li>
                @endforeach
            </ul>
        @else
            <p>No new products</p>
        @endif
    </div>

    <div style="background: #f0f0f0; padding: 20px; margin: 20px 0; border-radius: 8px;">
        <h2>Featured Products ({{ $featuredProducts->count() }})</h2>
        @if($featuredProducts->count() > 0)
            <ul>
                @foreach($featuredProducts as $product)
                    <li>{{ $product->name }} - ₪{{ $product->price }}</li>
                @endforeach
            </ul>
        @else
            <p>No featured products</p>
        @endif
    </div>

    <div style="background: #f0f0f0; padding: 20px; margin: 20px 0; border-radius: 8px;">
        <h2>Bestsellers ({{ $bestsellerProducts->count() }})</h2>
        @if($bestsellerProducts->count() > 0)
            <ul>
                @foreach($bestsellerProducts as $product)
                    <li>{{ $product->name }} - ₪{{ $product->price }}</li>
                @endforeach
            </ul>
        @else
            <p>No bestseller products</p>
        @endif
    </div>

    <div style="background: #f0f0f0; padding: 20px; margin: 20px 0; border-radius: 8px;">
        <h2>On Sale ({{ $onSaleProducts->count() }})</h2>
        @if($onSaleProducts->count() > 0)
            <ul>
                @foreach($onSaleProducts as $product)
                    <li>{{ $product->name }} - ₪{{ $product->price }} → ₪{{ $product->sale_price }}</li>
                @endforeach
            </ul>
        @else
            <p>No on-sale products</p>
        @endif
    </div>

    <a href="{{ route('home') }}" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">
        Back to Home
    </a>
</div>
@endsection
