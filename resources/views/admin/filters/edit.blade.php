@extends('admin.layout')

@section('title', __('messages.edit_filter'))

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1>{{ __('messages.edit_filter') }}: {{ $filter->title_en }}</h1>
        <p>{{ __('messages.filter_information') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.filters.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' || app()->getLocale() === 'he' ? 'right' : 'left' }}"></i> {{ __('messages.back_to_filters') }}
        </a>
    </div>
</div>

@include('admin.filters._form', [
    'filter' => $filter,
    'action' => route('admin.filters.update', $filter),
    'method' => 'PUT',
    'submitLabel' => __('messages.update_filter_btn'),
    'categories' => $categories,
])
@endsection
