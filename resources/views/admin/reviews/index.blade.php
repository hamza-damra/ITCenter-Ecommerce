@extends('admin.layout')

@section('title', __('messages.reviews'))

@section('content')
<div class="admin-page-container">
    <div class="page-header">
        <h1><i class="fas fa-star"></i> {{ __('messages.reviews') }}</h1>
        <div class="page-actions" style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap; justify-content:flex-end;">
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="search-form" style="display:flex; gap:.5rem; align-items:center;">
                <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="{{ __('messages.search') }}..." style="max-width:280px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> {{ __('messages.search') }}</button>
                @if($search)
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> {{ __('messages.clear') }}</a>
                @endif
            </form>

            <form id="delete-all-reviews-form" method="POST" action="{{ route('admin.reviews.delete-all') }}" style="display:inline-block;">
                @csrf
                <button type="button" class="btn btn-danger" onclick="return confirmDeleteAllReviews();" {{ ($totalReviews ?? 0) == 0 ? 'disabled' : '' }} title="{{ ($totalReviews ?? 0) == 0 ? __('messages.no_records_to_delete') : '' }}">

                    <i class="fas fa-exclamation-triangle"></i> {{ __('messages.delete_all') }} {{ __('messages.reviews') }}
                </button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">
    @if (session('info'))
        <div class="alert alert-info" role="alert">{{ session('info') }}</div>
    @endif

            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>{{ __('messages.product') }}</th>
                        <th>{{ __('messages.user') }}</th>
                        <th>{{ __('messages.rating') }}</th>
                        <th>{{ __('messages.comment') }}</th>
                        <th>{{ __('messages.date') }}</th>
                        <th class="text-end">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                        <tr>
                            <td>
                                @if($review->product)
                                    <div><strong>{{ $review->product->name }}</strong></div>
                                    <div class="text-muted" style="font-size:.85rem;">{{ $review->product->slug }}</div>
                                @else
                                    <em class="text-muted">—</em>
                                @endif
                            </td>
                            <td>
                                @if($review->user)
                                    <div>{{ $review->user->name }}</div>
                                    <div class="text-muted" style="font-size:.85rem;">{{ $review->user->email }}</div>
                                @else
                                    <em class="text-muted">—</em>
                                @endif
                            </td>
                            <td>
                                <div>
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= (int)$review->rating)
                                            <i class="fas fa-star" style="color:#f5c518;"></i>
                                        @else
                                            <i class="far fa-star" style="color:#ddd;"></i>
                                        @endif
                                    @endfor
                                    <span class="text-muted">({{ number_format((float)$review->rating, 1) }})</span>
                                </div>
                            </td>
                            <td style="max-width:480px;">
                                {{ \Illuminate\Support\Str::limit($review->comment, 160) }}
                            </td>
                            <td>
                                <div>{{ $review->created_at?->format('Y-m-d H:i') }}</div>
                            </td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}" style="display:inline-block;" class="delete-review-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> {{ __('messages.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">{{ __('messages.no_results') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $reviews->links() }}
        </div>
    </div>
<style>
/* Fade-out animation for deleted rows */
@keyframes fadeOutRow {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(-20px);
    }
}

.deleting-row {
    animation: fadeOutRow 0.4s ease-out forwards;
}

/* Toast notification for admin */
.admin-toast {
    position: fixed;
    top: 80px;
    right: 20px;
    background: white;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 10000;
    opacity: 0;
    transform: translateX(400px);
    transition: all 0.3s ease;
    max-width: 400px;
}

.admin-toast.show {
    opacity: 1;
    transform: translateX(0);
}

.admin-toast-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.admin-toast-content i {
    font-size: 1.25rem;
}

.admin-toast-success {
    border-left: 4px solid #28a745;
}

.admin-toast-success i {
    color: #28a745;
}

.admin-toast-error {
    border-left: 4px solid #dc3545;
}

.admin-toast-error i {
    color: #dc3545;
}

.admin-toast-info {
    border-left: 4px solid #0d6efd;
}

.admin-toast-info i {
    color: #0d6efd;
}
</style>

<script>
// Toast notification for admin
function showAdminToast(message, type = 'info') {
    const existingToast = document.querySelector('.admin-toast');
    if (existingToast) {
        existingToast.remove();
    }

    const toast = document.createElement('div');
    toast.className = `admin-toast admin-toast-${type}`;
    toast.innerHTML = `
        <div class="admin-toast-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('show');
    }, 10);

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

// Handle individual review deletion with animation
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('.delete-review-form');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const row = this.closest('tr');
            row.classList.add('deleting-row');

            // Submit form after animation starts
            setTimeout(() => {
                this.submit();
            }, 200);
        });
    });
});

function confirmDeleteAllReviews() {
    var total = {{ (int)($totalReviews ?? 0) }};
    if (total <= 0) {
        showAdminToast('{{ __('messages.no_records_to_delete') }}', 'info');
        return false;
    }

    // Show confirmation toast instead of alert
    showAdminToast('Deleting all ' + total + ' reviews...', 'info');

    // Submit after a short delay to show the toast
    setTimeout(() => {
        document.getElementById('delete-all-reviews-form').submit();
    }, 800);

    return false;
}
</script>

</div>
@endsection

