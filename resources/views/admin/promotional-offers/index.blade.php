@extends('admin.layout')

@section('content')
<div class="admin-content">
    <div class="content-header">
        <div>
            <h1><i class="fas fa-bullhorn"></i> إدارة الإعلانات الترويجية</h1>
            <p>إدارة العروض والإعلانات التي تظهر في الصفحة الرئيسية</p>
        </div>
        <a href="{{ route('admin.promotional-offers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة عرض جديد
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
                        <th>الصورة</th>
                        <th>العنوان</th>
                        <th>المنتج</th>
                        <th>السعر الأصلي</th>
                        <th>سعر العرض</th>
                        <th>الخصم</th>
                        <th>البداية</th>
                        <th>النهاية</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
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
                                {{ $offer->is_active ? 'نشط' : 'غير نشط' }}
                            </button>
                        </td>
                        <td class="action-btns">
                            <a href="{{ route('admin.promotional-offers.edit', $offer->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.promotional-offers.destroy', $offer->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                            <p>لا توجد عروض حالياً</p>
                            <a href="{{ route('admin.promotional-offers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة عرض جديد
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
    if (!confirm('هل تريد تغيير حالة العرض؟')) return;
    
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
