@extends('tenant-public.layout')

@section('title', ($page->meta_title ?? $page->title) . ' - ' . $website->name)
@section('meta_description', $page->meta_description ?? '')

@section('content')
<main>
    <div class="container">
        <div class="page-card">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('public.home') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $page->title }}</li>
                </ol>
            </nav>
            <h1 class="fw-bold mb-2">{{ $page->title }}</h1>
            <p class="text-muted small mb-4"><i class="far fa-calendar-plus me-1"></i> Created {{ $page->created_at->format('F d, Y') }}</p>
            <div class="content">{!! $page->content !!}</div>
            <hr class="my-4">
            <a href="{{ route('public.home') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i> Back to Home
            </a>
        </div>
    </div>
</main>
@endsection
