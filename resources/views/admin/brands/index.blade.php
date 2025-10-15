@extends('admin.layout')

@section('title', 'Brands')

@section('content')
<div class="top-bar">
    <h1>Brands</h1>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">+ Add New Brand</a>
</div>

<div class="content-box">
    <table>
        <thead>
            <tr>
                <th>Logo</th>
                <th>Name</th>
                <th>Website</th>
                <th>Featured</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($brands as $brand)
            <tr>
                <td>
                    @if($brand->logo)
                        <img src="{{ $brand->logo }}" alt="{{ $brand->name }}" class="product-thumb">
                    @else
                        <span>No logo</span>
                    @endif
                </td>
                <td>{{ $brand->name }}</td>
                <td>
                    @if($brand->website)
                        <a href="{{ $brand->website }}" target="_blank">{{ $brand->website }}</a>
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($brand->is_featured)
                        <span class="badge badge-success">Yes</span>
                    @else
                        <span class="badge badge-warning">No</span>
                    @endif
                </td>
                <td>
                    @if($brand->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No brands found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $brands->links() }}
    </div>
</div>
@endsection
