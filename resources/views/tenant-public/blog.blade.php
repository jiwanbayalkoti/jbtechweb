@extends('tenant-public.layout')

@section('title', ($post->meta_title ?? $post->title) . ' - ' . ($website->name ?? $tenant->name))
@section('meta_description', $post->meta_description ?? '')

@section('content')
<main>
    <div class="container">
        <div class="page-card">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('public.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('public.blog.index') }}">Blog</a></li>
                    <li class="breadcrumb-item active">{{ $post->title }}</li>
                </ol>
            </nav>
            <article>
                <h1 class="fw-bold mb-3">{{ $post->title }}</h1>
                <p class="text-muted mb-4">
                    <i class="far fa-calendar-alt me-1"></i> {{ $post->published_at?->format('F d, Y') }}
                    <span class="mx-2">•</span>
                    <i class="far fa-calendar-plus me-1"></i> Created {{ $post->created_at->format('M d, Y') }}
                    <span class="mx-2">•</span>
                    <i class="far fa-user me-1"></i> {{ $post->user->name ?? 'Admin' }}
                </p>
                <div class="content">{!! $post->content !!}</div>
            </article>
            <hr class="my-4">
            <a href="{{ route('public.blog.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>
</main>
@endsection
