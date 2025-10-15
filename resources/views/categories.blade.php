@extends('layouts.app')

@section('title', 'Categories - IT Center')

@section('content')
<style>
    .categories-section {
        padding: 3rem 2rem;
        background: #fff;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
    }

    .section-header h2 {
        font-size: 2rem;
        color: #333;
    }

    .view-more {
        color: #333;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }

    .view-more:hover {
        color: #667eea;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 3rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .category-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .category-item:hover {
        transform: translateY(-10px);
    }

    .category-icon {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s;
        background: #fff;
    }

    .category-item:hover .category-icon {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .category-icon i {
        font-size: 3rem;
    }

    .category-name {
        font-size: 0.95rem;
        color: #333;
        font-weight: 500;
    }

    .icon-computers { color: #ff4757; }
    .icon-printer { color: #5f27cd; }
    .icon-mobile { color: #00d2d3; }
    .icon-bag { color: #1e90ff; }
    .icon-laptop { color: #ff6348; }
    .icon-accessories { color: #2e86de; }
    .icon-monitor { color: #341f97; }
    .icon-case { color: #ee5a6f; }
    .icon-motherboard { color: #0abde3; }
    .icon-cpu { color: #2d3436; }
    .icon-cooler { color: #00a8ff; }
    .icon-gpu { color: #e74c3c; }
</style>

<div class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2>Categories</h2>
            <a href="#" class="view-more">
                View More <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="categories-grid">
            <div class="category-item">
                <div class="category-icon">
                    <i class="fas fa-desktop icon-computers"></i>
                </div>
                <div class="category-name">computers</div>
            </div>

            <div class="category-item">
                <div class="category-icon">
                    <i class="fas fa-print icon-printer"></i>
                </div>
                <div class="category-name">Printer</div>
            </div>

            <div class="category-item">
                <div class="category-icon">
                    <i class="fas fa-mobile-alt icon-mobile"></i>
                </div>
                <div class="category-name">Mobile accessories</div>
            </div>

            <div class="category-item">
                <div class="category-icon">
                    <i class="fas fa-briefcase icon-bag"></i>
                </div>
                <div class="category-name">LAPTOP BAG</div>
            </div>

            <div class="category-item">
                <div class="category-icon">
                    <i class="fas fa-laptop icon-laptop"></i>
                </div>
                <div class="category-name">Laptop</div>
            </div>

            <div class="category-item">
                <div class="category-icon">
                    <i class="fas fa-keyboard icon-accessories"></i>
                </div>
                <div class="category-name">Laptop Accessories</div>
            </div>

            <div class="category-item">
                <div class="category-icon">
                    <i class="fas fa-tv icon-monitor"></i>
                </div>
                <div class="category-name">MONITORS</div>
            </div>

            <div class="category-item">
                <div class="category-icon">
                    <i class="fas fa-server icon-case"></i>
                </div>
                <div class="category-name">Computer Case</div>
            </div>

            <div class="category-item">
                <div class="category-icon">
                    <i class="fas fa-memory icon-motherboard"></i>
                </div>
                <div class="category-name">Motherboard</div>
            </div>

            <div class="category-item">
                <div class="category-icon">
                    <i class="fas fa-microchip icon-cpu"></i>
                </div>
                <div class="category-name">CPU</div>
            </div>

            <div class="category-item">
                <div class="category-icon">
                    <i class="fas fa-fan icon-cooler"></i>
                </div>
                <div class="category-name">COOLER</div>
            </div>

            <div class="category-item">
                <div class="category-icon">
                    <i class="fas fa-hdd icon-gpu"></i>
                </div>
                <div class="category-name">GRAPHIC CARD</div>
            </div>
        </div>
    </div>
</div>
@endsection
