@extends('admin.layout')

@section('title', __('messages.tags_management'))

@section('content')
<style>
    /* Page-specific styles that extend unified components */
    
    /* Tag Name Cell */
    .tag-name-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tag-name-cell .tag-icon {
        font-size: 1rem;
    }

    .tag-name-cell .tag-name-secondary {
        color: #64748b;
        font-size: 0.75rem;
    }

    /* Color Swatch */
    .color-swatch {
        display: inline-block;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        border: 2px solid rgba(0, 0, 0, 0.1);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Slug Code */
    .slug-code {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.8rem;
        background: #f1f5f9;
        padding: 0.35rem 0.6rem;
        border-radius: 6px;
        color: #475569;
    }

    /* Products Count Badge */
    .products-count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        padding: 0.35rem 0.6rem;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #1e40af;
        font-weight: 700;
        font-size: 0.85rem;
        border-radius: 8px;
    }

    /* Header Actions */
    .header-actions .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-indigo) 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 14px rgba(14, 165, 233, 0.3);
    }

    .header-actions .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4);
    }

    /* Action Buttons */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.5rem 0.85rem;
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-edit {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #475569;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        transform: translateY(-1px);
    }

    .btn-delete {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #dc2626;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
        transform: translateY(-1px);
    }

    /* Pagination Wrapper */
    .pagination-wrapper {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid var(--border, #e2e8f0);
        background: linear-gradient(135deg, var(--bg-secondary, #f8fafc) 0%, var(--bg-tertiary, #f1f5f9) 100%);
    }
</style>

<!-- Page Header - Using unified admin-hero component -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-tags"></i>
            </div>
            <div>
                <h1>{{ __('messages.tags_management') }}</h1>
                <p>{{ __('messages.manage_product_tags') }}</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.tags.create') }}" class="btn-add">
                <i class="fas fa-plus-circle"></i> {{ __('messages.add_new_tag') }}
            </a>
        </div>
    </div>
</div>

<!-- Tags Table - Using unified admin-table-container component -->
<div class="admin-table-container">
    <div class="admin-table-header">
        <h3><i class="fas fa-list"></i> {{ __('messages.tags_list') ?? __('messages.tags') }}</h3>
    </div>
    
    @if($tags->count() > 0)
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>{{ __('messages.tag_name') }}</th>
                    <th>{{ __('messages.slug') }}</th>
                    <th>{{ __('messages.color') }}</th>
                    <th>{{ __('messages.products_count') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tags as $tag)
                <tr>
                    <td>
                        <div class="tag-name-cell">
                            @if($tag->icon)
                                <i class="{{ $tag->icon }} tag-icon" style="color: {{ $tag->color }}"></i>
                            @endif
                            <span>{{ $tag->name_en }}</span>
                            <span class="tag-name-secondary">({{ $tag->name_ar }})</span>
                        </div>
                    </td>
                    <td><code class="slug-code">{{ $tag->slug }}</code></td>
                    <td>
                        <span class="color-swatch" style="background: {{ $tag->color }};"></span>
                    </td>
                    <td>
                        <span class="products-count-badge">{{ $tag->products_count }}</span>
                    </td>
                    <td>
                        @if($tag->is_active)
                            <span class="status-badge status-active">{{ __('messages.active') }}</span>
                        @else
                            <span class="status-badge status-inactive">{{ __('messages.inactive') }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.tags.edit', $tag) }}" class="btn-action btn-edit" title="{{ __('messages.edit') }}">
                                <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                            </a>
                            <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('messages.confirm_delete_tag') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="{{ __('messages.delete') }}">
                                    <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($tags->hasPages())
    <div class="pagination-wrapper">
        {{ $tags->links() }}
    </div>
    @endif
    @else
    <!-- Empty State - Using unified admin-empty-state component -->
    <div class="admin-empty-state">
        <div class="admin-empty-state-icon">
            <i class="fas fa-tags"></i>
        </div>
        <h3>{{ __('messages.no_tags_found') }}</h3>
        <p>{{ __('messages.no_tags_description') }}</p>
        <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> {{ __('messages.create_first_tag') }}
        </a>
    </div>
    @endif
</div>
@endsection
