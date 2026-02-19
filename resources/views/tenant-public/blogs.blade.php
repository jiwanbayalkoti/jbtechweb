@extends('tenant-public.layout')

@section('title', 'Blog - ' . ($website->name ?? $tenant->name))
@section('meta_description', 'Latest articles and news from ' . ($website->name ?? $tenant->name))

@section('content')
<main>
    <div class="container">
        <div class="page-card py-5">
            <h1 class="fw-bold mb-4">Blog</h1>
            <p class="text-muted mb-5">Latest articles and updates</p>

            @if($blogs->isNotEmpty())
            <div class="row g-4">
                @foreach($blogs as $post)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tenant.public.blog', [$tenant->slug, $post->slug]) }}" class="card card-custom text-decoration-none text-dark h-100 overflow-hidden">
                        <div class="card-body">
                            <h5 class="fw-bold mb-2">{{ $post->title }}</h5>
                            <div class="content content-excerpt small text-muted mb-3">
                            {!! $post->content ?? '' !!}
                        </div>
                            <p class="mb-0 small">
                                <i class="far fa-calendar-alt me-1"></i> {{ $post->published_at?->format('M d, Y') }}
                                <span class="mx-2">•</span>
                                <i class="far fa-calendar-plus me-1"></i> {{ $post->created_at->format('M d, Y') }}
                                <span class="mx-2">•</span>
                                <i class="far fa-user me-1"></i> {{ $post->user->name ?? 'Admin' }}
                            </p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="mt-5 d-flex justify-content-center">
                {{ $blogs->links() }}
            </div>
            @else
            <p class="text-muted text-center py-5">No blog posts yet. Check back soon!</p>
            @endif
        </div>
    </div>
</main>
@endsection
