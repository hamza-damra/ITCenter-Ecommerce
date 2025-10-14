@extends('layouts.app')

@section('title', 'About Us - IT Center')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>About IT Center</h1>
        <p>Learn more about our company and mission</p>
    </div>
</div>

<div class="container">
    <div style="max-width: 800px; margin: 0 auto;">
        <h2>Who We Are</h2>
        <p style="margin: 1rem 0; line-height: 1.8;">
            IT Center is a leading provider of technology products and solutions. We have been serving customers
            for over 10 years, offering a wide range of computers, accessories, and IT services.
        </p>

        <h2 style="margin-top: 2rem;">Our Mission</h2>
        <p style="margin: 1rem 0; line-height: 1.8;">
            Our mission is to provide high-quality technology products at competitive prices while delivering
            exceptional customer service. We strive to be your trusted partner in all things technology.
        </p>

        <h2 style="margin-top: 2rem;">What We Offer</h2>
        <ul style="margin: 1rem 0 1rem 2rem; line-height: 1.8;">
            <li>Wide selection of computers and laptops</li>
            <li>Computer components and accessories</li>
            <li>Networking equipment</li>
            <li>Software solutions</li>
            <li>Technical support and services</li>
            <li>Custom PC builds</li>
        </ul>

        <h2 style="margin-top: 2rem;">Why Choose Us</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
            <div style="text-align: center; padding: 1.5rem; border: 1px solid #ddd; border-radius: 8px;">
                <h3 style="color: #4CAF50;">10+ Years</h3>
                <p>Experience</p>
            </div>
            <div style="text-align: center; padding: 1.5rem; border: 1px solid #ddd; border-radius: 8px;">
                <h3 style="color: #4CAF50;">5000+</h3>
                <p>Happy Customers</p>
            </div>
            <div style="text-align: center; padding: 1.5rem; border: 1px solid #ddd; border-radius: 8px;">
                <h3 style="color: #4CAF50;">24/7</h3>
                <p>Support</p>
            </div>
        </div>
    </div>
</div>
@endsection
