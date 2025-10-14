@extends('layouts.app')

@section('title', 'Special Offers - IT Center')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Special Offers</h1>
        <p>Check out our latest deals and promotions</p>
    </div>
</div>

<div class="container">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 2rem;">
        <div style="border: 2px solid #4CAF50; padding: 1.5rem; border-radius: 8px; background: #f9f9f9;">
            <div style="background: #4CAF50; color: white; padding: 0.5rem; margin: -1.5rem -1.5rem 1rem; border-radius: 6px 6px 0 0;">
                <strong>HOT DEAL - 30% OFF</strong>
            </div>
            <h3>Lenovo ThinkPad</h3>
            <p style="text-decoration: line-through; color: #999;">$1,299</p>
            <p style="font-size: 1.8rem; color: #4CAF50; font-weight: bold;">$909</p>
            <p>Business laptop with excellent keyboard.</p>
            <p style="color: #e74c3c; margin-top: 1rem;"><strong>Offer ends soon!</strong></p>
        </div>
        <div style="border: 2px solid #4CAF50; padding: 1.5rem; border-radius: 8px; background: #f9f9f9;">
            <div style="background: #4CAF50; color: white; padding: 0.5rem; margin: -1.5rem -1.5rem 1rem; border-radius: 6px 6px 0 0;">
                <strong>BUNDLE DEAL</strong>
            </div>
            <h3>Gaming Setup Bundle</h3>
            <p style="text-decoration: line-through; color: #999;">$899</p>
            <p style="font-size: 1.8rem; color: #4CAF50; font-weight: bold;">$699</p>
            <p>Gaming keyboard, mouse, and headset bundle.</p>
            <p style="color: #e74c3c; margin-top: 1rem;"><strong>Limited stock!</strong></p>
        </div>
        <div style="border: 2px solid #4CAF50; padding: 1.5rem; border-radius: 8px; background: #f9f9f9;">
            <div style="background: #4CAF50; color: white; padding: 0.5rem; margin: -1.5rem -1.5rem 1rem; border-radius: 6px 6px 0 0;">
                <strong>CLEARANCE - 40% OFF</strong>
            </div>
            <h3>HP LaserJet Printer</h3>
            <p style="text-decoration: line-through; color: #999;">$499</p>
            <p style="font-size: 1.8rem; color: #4CAF50; font-weight: bold;">$299</p>
            <p>Professional laser printer with WiFi.</p>
            <p style="color: #e74c3c; margin-top: 1rem;"><strong>While supplies last!</strong></p>
        </div>
        <div style="border: 2px solid #4CAF50; padding: 1.5rem; border-radius: 8px; background: #f9f9f9;">
            <div style="background: #4CAF50; color: white; padding: 0.5rem; margin: -1.5rem -1.5rem 1rem; border-radius: 6px 6px 0 0;">
                <strong>WEEKEND SPECIAL</strong>
            </div>
            <h3>SSD Storage Upgrade</h3>
            <p style="text-decoration: line-through; color: #999;">$199</p>
            <p style="font-size: 1.8rem; color: #4CAF50; font-weight: bold;">$149</p>
            <p>1TB NVMe SSD - Lightning fast storage.</p>
            <p style="color: #e74c3c; margin-top: 1rem;"><strong>This weekend only!</strong></p>
        </div>
    </div>
</div>
@endsection
