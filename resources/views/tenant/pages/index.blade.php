@extends('layouts.tenant')

@section('title', 'Pages')
@section('page-title', 'Pages')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Pages</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('tenant.pages.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Page</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Website</th>
                    <th>Status</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                <tr>
                    <td>{{ $page->title }}</td>
                    <td><code>{{ $page->slug }}</code></td>
                    <td>{{ $page->website->name ?? '-' }}</td>
                    <td><span class="badge badge-{{ $page->is_published ? 'success' : 'secondary' }}">{{ $page->is_published ? 'Published' : 'Draft' }}</span></td>
                    <td>
                        <a href="{{ route('tenant.pages.edit', $page) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('tenant.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this page?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">No pages yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $pages->links() }}</div>
</div>
@endsection
