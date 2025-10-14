@extends('layouts.app')

@section('title', 'Brands - IT Center')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Our Brands</h1>
        <p>We partner with the world's leading technology brands</p>
    </div>
</div>

<div class="container">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-top: 2rem;">
        <div style="border: 1px solid #ddd; padding: 2rem; text-align: center; border-radius: 8px;">
            <h3>Dell</h3>
        </div>
        <div style="border: 1px solid #ddd; padding: 2rem; text-align: center; border-radius: 8px;">
            <h3>HP</h3>
        </div>
        <div style="border: 1px solid #ddd; padding: 2rem; text-align: center; border-radius: 8px;">
            <h3>Lenovo</h3>
        </div>
        <div style="border: 1px solid #ddd; padding: 2rem; text-align: center; border-radius: 8px;">
            <h3>Apple</h3>
        </div>
        <div style="border: 1px solid #ddd; padding: 2rem; text-align: center; border-radius: 8px;">
            <h3>ASUS</h3>
        </div>
        <div style="border: 1px solid #ddd; padding: 2rem; text-align: center; border-radius: 8px;">
            <h3>Acer</h3>
        </div>
        <div style="border: 1px solid #ddd; padding: 2rem; text-align: center; border-radius: 8px;">
            <h3>Microsoft</h3>
        </div>
        <div style="border: 1px solid #ddd; padding: 2rem; text-align: center; border-radius: 8px;">
            <h3>Intel</h3>
        </div>
        <div style="border: 1px solid #ddd; padding: 2rem; text-align: center; border-radius: 8px;">
            <h3>AMD</h3>
        </div>
        <div style="border: 1px solid #ddd; padding: 2rem; text-align: center; border-radius: 8px;">
            <h3>NVIDIA</h3>
        </div>
        <div style="border: 1px solid #ddd; padding: 2rem; text-align: center; border-radius: 8px;">
            <h3>Logitech</h3>
        </div>
        <div style="border: 1px solid #ddd; padding: 2rem; text-align: center; border-radius: 8px;">
            <h3>Samsung</h3>
        </div>
    </div>
</div>
@endsection
