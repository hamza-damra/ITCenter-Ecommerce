@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="top-bar">
    <h1>Dashboard</h1>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Products</h3>
        <div class="number">{{ $stats['total_products'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Active Products</h3>
        <div class="number">{{ $stats['active_products'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Categories</h3>
        <div class="number">{{ $stats['total_categories'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Brands</h3>
        <div class="number">{{ $stats['total_brands'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Featured Products</h3>
        <div class="number">{{ $stats['featured_products'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Out of Stock</h3>
        <div class="number">{{ $stats['out_of_stock'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Total Reviews</h3>
        <div class="number">{{ $stats['total_reviews'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Active Offers</h3>
        <div class="number">{{ $stats['active_offers'] }}</div>
    </div>
</div>

<div class="content-box">
    <h2 style="margin-bottom: 20px;">Recent Products</h2>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recent_products as $product)
            <tr>
                <td><img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="product-thumb"></td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td>
                <td>{{ $product->brand->name ?? 'N/A' }}</td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>{{ $product->stock_quantity }}</td>
                <td>
                    @if($product->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Inactive</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
