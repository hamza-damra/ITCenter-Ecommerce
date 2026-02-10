{{-- Custom Content Section --}}
@if(isset($section) && $section->is_active)
    @php
        $locale = app()->getLocale();
        $customHtml = $section->getSetting("custom_html_{$locale}") ?: $section->getSetting('custom_html_en');
    @endphp
    @if($customHtml)
        <div class="container">
            <div class="custom-content-section" style="{{ $section->getSetting('background_color') ? 'background-color: ' . $section->getSetting('background_color') . ';' : '' }}">
                @if($section->title)
                    <h2 class="custom-section-title">{{ $section->title }}</h2>
                @endif
                @if($section->subtitle)
                    <p class="custom-section-subtitle">{{ $section->subtitle }}</p>
                @endif
                <div class="custom-section-body">
                    {!! $customHtml !!}
                </div>
            </div>
        </div>
    @endif
@endif
