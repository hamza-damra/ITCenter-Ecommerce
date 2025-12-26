@extends('admin.layout')

@section('title', __('messages.specification_templates'))

@section('content')
<style>
    .template-stats {
        display: flex;
        gap: 8px;
        margin-top: 8px;
    }
    .template-stat {
        font-size: 12px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .field-count-badge {
        background: #e0f2fe;
        color: #0369a1;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    .category-badge {
        background: #f0fdf4;
        color: #166534;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-clipboard-list"></i> {{ __('messages.specification_templates') }}</h1>
        <p>{{ __('messages.manage_category_spec_templates') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.spec-templates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('messages.create_template') }}
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        @if($templates->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.template_name') }}</th>
                        <th>{{ __('messages.category') }}</th>
                        <th>{{ __('messages.fields') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $template->name_en }}</strong>
                                    @if($template->name_ar)
                                        <div style="color: #64748b; font-size: 12px;">{{ $template->name_ar }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="category-badge">
                                    <i class="fas fa-folder"></i>
                                    {{ $template->category?->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="field-count-badge">
                                    <i class="fas fa-list"></i>
                                    {{ $template->fields->count() }} {{ __('messages.fields') }}
                                </span>
                            </td>
                            <td>
                                @if($template->is_active)
                                    <span class="status-badge" style="background: #dcfce7; color: #166534;">
                                        <i class="fas fa-check-circle"></i> {{ __('messages.active') }}
                                    </span>
                                @else
                                    <span class="status-badge" style="background: #fee2e2; color: #991b1b;">
                                        <i class="fas fa-times-circle"></i> {{ __('messages.inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.spec-templates.edit', $template) }}" 
                                       class="btn btn-sm btn-secondary" 
                                       title="{{ __('messages.edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.spec-templates.destroy', $template) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('{{ __('messages.confirm_delete_template') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="{{ __('messages.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $templates->links() }}
            </div>
        @else
            <div class="admin-empty-state">
                <div class="admin-empty-state-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3>{{ __('messages.no_templates_found') }}</h3>
                <p>{{ __('messages.create_first_template') }}</p>
                <a href="{{ route('admin.spec-templates.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('messages.create_template') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection






