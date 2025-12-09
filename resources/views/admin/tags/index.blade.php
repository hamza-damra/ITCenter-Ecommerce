@extends('admin.layout')

@section('title', __('messages.tags_management'))

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-tags"></i> {{ __('messages.tags_management') }}</h1>
        <p>{{ __('messages.manage_product_tags') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('messages.add_new_tag') }}
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($tags->count() > 0)
            <table class="data-table">
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
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    @if($tag->icon)
                                        <i class="{{ $tag->icon }}" style="color: {{ $tag->color }}"></i>
                                    @endif
                                    <span>{{ $tag->name_en }}</span>
                                    <span style="color: #64748b; font-size: 12px;">({{ $tag->name_ar }})</span>
                                </div>
                            </td>
                            <td><code>{{ $tag->slug }}</code></td>
                            <td>
                                <span style="display: inline-block; width: 24px; height: 24px; background: {{ $tag->color }}; border-radius: 4px;"></span>
                            </td>
                            <td>{{ $tag->products_count }}</td>
                            <td>
                                @if($tag->is_active)
                                    <span class="status-badge status-active">{{ __('messages.active') }}</span>
                                @else
                                    <span class="status-badge status-inactive">{{ __('messages.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-secondary" title="{{ __('messages.edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('messages.confirm_delete_tag') }}')">
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
                {{ $tags->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-tags"></i>
                <h3>{{ __('messages.no_tags_found') }}</h3>
                <p>{{ __('messages.no_tags_description') }}</p>
                <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('messages.create_first_tag') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
