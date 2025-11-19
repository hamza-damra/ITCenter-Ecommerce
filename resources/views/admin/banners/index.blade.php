@extends('admin.layout')

@section('title', 'إدارة البانرات')

@section('content')
<style>
    .banners-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .banners-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
    }

    .create-btn {
        padding: 12px 24px;
        background: linear-gradient(135deg, var(--primary) 0%, #1e40af 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .create-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        color: white;
    }

    .banners-table-wrapper {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .banners-table {
        width: 100%;
        border-collapse: collapse;
    }

    .banners-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 2px solid #e2e8f0;
    }

    .banners-table th {
        padding: 18px 20px;
        text-align: {{ is_rtl() ? 'right' : 'left' }};
        font-weight: 700;
        color: var(--dark);
        font-size: 13px;
    }

    .banners-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .banner-image {
        width: 80px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
    }

    .section-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .section-strong_offers { background: #fef3c7; color: #92400e; }
    .section-gift_ideas { background: #dbeafe; color: #1e40af; }
    .section-hero { background: #e9d5ff; color: #6b21a8; }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-active { background: #dcfce7; color: #166534; }
    .status-inactive { background: #fee2e2; color: #991b1b; }

    .action-btns {
        display: flex;
        gap: 8px;
    }

    .btn-edit, .btn-delete {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-edit:hover {
        background: #1e40af;
        color: white;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-delete:hover {
        background: #991b1b;
        color: white;
    }
</style>

<div class="banners-header">
    <h1><i class="fas fa-images"></i> إدارة البانرات</h1>
    <a href="{{ route('admin.banners.create') }}" class="create-btn">
        <i class="fas fa-plus"></i> إضافة بانر جديد
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($banners->count() > 0)
<div class="banners-table-wrapper">
    <table class="banners-table">
        <thead>
            <tr>
                <th>الصورة</th>
                <th>العنوان</th>
                <th>القسم</th>
                <th>نوع الرابط</th>
                <th>الترتيب</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banners as $banner)
            <tr>
                <td>
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title_en }}" class="banner-image">
                </td>
                <td>
                    <strong>{{ $banner->title_en }}</strong><br>
                    <small>{{ $banner->title_ar }}</small>
                </td>
                <td>
                    <span class="section-badge section-{{ $banner->section }}">
                        @if($banner->section === 'strong_offers')
                            عروض قوية
                        @elseif($banner->section === 'gift_ideas')
                            أفكار هدايا
                        @else
                            هيرو
                        @endif
                    </span>
                </td>
                <td>
                    @if($banner->link_type === 'external')
                        <i class="fas fa-external-link-alt"></i> خارجي
                    @elseif($banner->link_type === 'products')
                        <i class="fas fa-box"></i> منتجات
                    @elseif($banner->link_type === 'category')
                        <i class="fas fa-folder"></i> فئة محددة
                    @else
                        <i class="fas fa-th"></i> كل الفئات
                    @endif
                </td>
                <td>{{ $banner->display_order }}</td>
                <td>
                    <span class="status-badge status-{{ $banner->is_active ? 'active' : 'inactive' }}">
                        {{ $banner->is_active ? 'نشط' : 'غير نشط' }}
                    </span>
                </td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn-edit">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div style="margin-top: 2rem;">
    {{ $banners->links() }}
</div>
@else
<div style="text-align: center; padding: 3rem; background: white; border-radius: 16px;">
    <i class="fas fa-images" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
    <h3>لا توجد بانرات</h3>
    <p>ابدأ بإضافة أول بانر!</p>
    <a href="{{ route('admin.banners.create') }}" class="create-btn" style="margin-top: 1rem;">
        <i class="fas fa-plus"></i> إضافة بانر جديد
    </a>
</div>
@endif

@endsection
