@extends('layouts.tenant')

@section('title', 'Blog')
@section('page-title', 'Blog')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Blog</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#blogModal" onclick="openBlogModal()">
            <i class="fas fa-plus"></i> Add Post
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blogs as $blog)
                <tr>
                    <td>{{ $blog->title }}</td>
                    <td>{{ $blog->user->name ?? '-' }}</td>
                    <td><span class="badge badge-{{ $blog->is_published ? 'success' : 'secondary' }}">{{ $blog->is_published ? 'Published' : 'Draft' }}</span></td>
                    <td>{{ $blog->created_at->format('M d, Y') }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#blogModal" onclick="editBlog('{{ route('tenant.blogs.update', $blog) }}', {{ json_encode($blog) }})"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteBlog('{{ route('tenant.blogs.destroy', $blog) }}', '{{ addslashes($blog->title) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">No blog posts yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $blogs->links() }}</div>
</div>

@include('tenant.blogs.modal')
@endsection

@push('scripts')
<script>
function openBlogModal() {
    $('#blogForm')[0].reset();
    $('#blogForm input[name="slug"]').data('manual', false);
    $('#blogForm').attr('action', '{{ route("tenant.blogs.store") }}').find('input[name="_method"]').remove();
    $('#blogModal .modal-title').text('Add Blog Post');
}
function editBlog(url, data) {
    $('#blogForm').attr('action', url);
    if (!$('#blogForm input[name="_method"]').length) $('#blogForm').append('<input type="hidden" name="_method" value="PUT">');
    $('input[name="title"]').val(data.title);
    $('input[name="slug"]').val(data.slug || '');
    $('#blogForm input[name="slug"]').data('manual', !!data.slug);
    $('textarea[name="content"]').val(data.content);
    $('input[name="is_published"]').prop('checked', data.is_published);
    $('#blogModal .modal-title').text('Edit Blog Post');
}
function deleteBlog(url, title) {
    JBAdmin.confirm({ title: 'Delete Post', message: 'Delete "' + title + '"?', confirmClass: 'btn-danger', onConfirm: function() {
        $.ajax({ url: url, method: 'DELETE', headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, success: function() { location.reload(); } });
    }});
}
JBAdmin.autoSlugFromTitle('#blogModal input[name="title"]', '#blogModal input[name="slug"]');
$('#blogForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({ url: $(this).attr('action'), method: $(this).find('input[name="_method"]').val() || 'POST', data: $(this).serialize(),
        success: function(res) { $('#blogModal').modal('hide'); location.href = res.redirect || location.href; },
        error: function(xhr) { alert(xhr.responseJSON?.message || 'Error'); }
    });
});
</script>
@endpush
