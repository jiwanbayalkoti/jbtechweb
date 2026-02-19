@extends('layouts.tenant')

@section('title', 'Edit Menu')
@section('page-title', 'Edit: ' . $menu->name)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('tenant.menus.index') }}">Menus</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Menu Items</h3>
                <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#itemModal">
                    <i class="fas fa-plus"></i> Add Item
                </button>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @forelse($menu->items->whereNull('parent_id') as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $item->title }} <small class="text-muted">— {{ $item->url ?: '(no link)' }}</small></span>
                        <div>
                            <form action="{{ route('tenant.menus.items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this item?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </li>
                    @foreach($item->children as $child)
                    <li class="list-group-item list-group-item-secondary pl-5 d-flex justify-content-between">
                        <span>{{ $child->title }} <small class="text-muted">— {{ $child->url ?: '(no link)' }}</small></span>
                        <form action="{{ route('tenant.menus.items.destroy', $child) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </li>
                    @endforeach
                    @empty
                    <li class="list-group-item">No items. Add one above.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Menu Settings</div>
            <form method="POST" action="{{ route('tenant.menus.update', $menu) }}">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $menu->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <select name="location" class="form-control">
                            <option value="header" {{ $menu->location === 'header' ? 'selected' : '' }}>Header</option>
                            <option value="footer" {{ $menu->location === 'footer' ? 'selected' : '' }}>Footer</option>
                            <option value="sidebar" {{ $menu->location === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('tenant.menus.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('tenant.menus.items.store', $menu) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Menu Item</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>URL</label>
                        <input type="text" name="url" class="form-control" placeholder="/about or https://...">
                    </div>
                    <div class="form-group">
                        <label>Parent (optional)</label>
                        <select name="parent_id" class="form-control">
                            <option value="">— None —</option>
                            @foreach($menu->items->whereNull('parent_id') as $i)
                            <option value="{{ $i->id }}">{{ $i->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Open in</label>
                        <select name="target" class="form-control">
                            <option value="_self">Same window</option>
                            <option value="_blank">New tab</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
