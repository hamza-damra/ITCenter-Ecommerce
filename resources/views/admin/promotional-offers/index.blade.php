@extends('admin.layout')

@section('content')
<div class="admin-content">
    <div class="content-header">
        <div>
            <h1><i class="fas fa-bullhorn"></i> {{ __('messages.promotional_offers_title') }}</h1>
            <p>{{ __('messages.promotional_offers_subtitle') }}</p>
        </div>
        <a href="{{ route('admin.promotional-offers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('messages.add_new_offer') }}
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.image') }}</th>
                        <th>{{ __('messages.title') }}</th>
                        <th>{{ __('messages.product') }}</th>
                        <th>{{ __('messages.original_price') }}</th>
                        <th>{{ __('messages.sale_price') }}</th>
                        <th>{{ __('messages.discount') }}</th>
                        <th>{{ __('messages.start_date') }}</th>
                        <th>{{ __('messages.end_date') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offers as $offer)
                    <tr>
                        <td>
                            @if($offer->product && $offer->product->main_image)
                            <img src="{{ $offer->product->main_image }}" alt="{{ $offer->title }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                            @else
                            <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image" style="color: #999;"></i>
                            </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $offer->title }}</strong>
                        </td>
                        <td>{{ $offer->product ? $offer->product->name : 'N/A' }}</td>
                        <td>₪{{ number_format($offer->original_price, 2) }}</td>
                        <td><strong style="color: #ff4757;">₪{{ number_format($offer->sale_price, 2) }}</strong></td>
                        <td>
                            <span class="badge badge-success">
                                {{ $offer->discount_percentage }}% (₪{{ number_format($offer->discount_amount, 2) }})
                            </span>
                        </td>
                        <td>{{ $offer->start_date->format('Y-m-d H:i') }}</td>
                        <td>{{ $offer->end_date->format('Y-m-d H:i') }}</td>
                        <td>
                            <button class="badge {{ $offer->is_active ? 'badge-success' : 'badge-danger' }}" 
                                    onclick="toggleActive({{ $offer->id }})"
                                    style="cursor: pointer; border: none;">
                                {{ $offer->is_active ? __('messages.active') : __('messages.inactive') }}
                            </button>
                        </td>
                        <td class="action-btns">
                            <a href="{{ route('admin.promotional-offers.edit', $offer->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.promotional-offers.destroy', $offer->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('messages.confirm_delete_offer') }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                            <p>{{ __('messages.no_offers_currently') }}</p>
                            <a href="{{ route('admin.promotional-offers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('messages.add_new_offer') }}
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($offers->hasPages())
        <div class="pagination-wrapper">
            {{ $offers->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function toggleActive(offerId) {
    if (!confirm('{{ __('messages.confirm_toggle_status') }}')) return;
    
    fetch(`/admin/promotional-offers/${offerId}/toggle-active`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
