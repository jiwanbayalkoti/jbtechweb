@extends('tenant-public.layout')

@section('title', $item->title . ' - ' . ($website->name ?? $tenant->name))
@section('meta_description', Str::limit(strip_tags($item->description ?? ''), 160))

@section('content')
<main>
    <div class="container">
        <div class="page-card">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('public.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('public.portfolio') }}">Portfolio</a></li>
                    <li class="breadcrumb-item active">{{ $item->title }}</li>
                </ol>
            </nav>
            @if($item->image)
            <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid rounded-3 mb-4 w-100" alt="{{ $item->title }}" style="max-height: 400px; object-fit: cover;">
            @endif
            <h1 class="fw-bold mb-3">{{ $item->title }}</h1>
            @if($item->category || $item->client || $item->project_date)
            <p class="text-muted mb-4">
                @if($item->category)<span class="badge bg-primary me-2">{{ $item->category }}</span>@endif
                @if($item->client)<span><i class="far fa-user me-1"></i> {{ $item->client }}</span>@endif
                @if($item->project_date)<span class="ms-2"><i class="far fa-calendar me-1"></i> {{ $item->project_date->format('F Y') }}</span>@endif
            </p>
            @endif
            <p class="text-muted small mb-4"><i class="far fa-calendar-plus me-1"></i> Added {{ $item->created_at->format('M d, Y') }}</p>
            <div class="content">{!! $item->description !!}</div>
            @if($item->project_url)
            <a href="{{ $item->project_url }}" target="_blank" rel="noopener" class="btn btn-primary mt-3"><i class="fas fa-external-link-alt me-2"></i> View Project</a>
            @endif
            <hr class="my-4">
            <a href="{{ route('public.portfolio') }}" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i> Back to Portfolio</a>
        </div>
    </div>
</main>
@endsection
