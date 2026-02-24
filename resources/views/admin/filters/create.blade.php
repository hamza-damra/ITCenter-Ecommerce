@extends('admin.layout')

@section('title', __('messages.create_filter'))

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1>{{ __('messages.create_filter') }}</h1>
        <p>{{ __('messages.filter_information') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.filters.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' || app()->getLocale() === 'he' ? 'right' : 'left' }}"></i> {{ __('messages.back_to_filters') }}
        </a>
    </div>
</div>

@include('admin.filters._form', [
    'filter' => null,
    'action' => route('admin.filters.store'),
    'method' => 'POST',
    'submitLabel' => __('messages.create_filter_btn'),
    'categories' => $categories,
])
@endsection
