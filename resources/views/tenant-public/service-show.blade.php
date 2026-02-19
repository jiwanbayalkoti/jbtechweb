@extends('tenant-public.layout')

@section('title', $service->title . ' - Services - ' . ($website->name ?? $tenant->name))
@section('meta_description', Str::limit(strip_tags($service->description ?? ''), 160))

@section('content')
<main>
    <div class="container">
        <div class="page-card">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('tenant.public.home', $tenant->slug) }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tenant.public.services', $tenant->slug) }}">Services</a></li>
                    <li class="breadcrumb-item active">{{ $service->title }}</li>
                </ol>
            </nav>
            <h1 class="fw-bold mb-2">{{ $service->title }}</h1>
            <p class="text-muted small mb-4"><i class="far fa-calendar-plus me-1"></i> {{ $service->created_at->format('M d, Y') }}</p>
            @if($service->icon ?? null)
            <p class="mb-4"><span class="text-primary" style="font-size: 3rem;"><i class="{{ $service->icon }}"></i></span></p>
            @endif
            <div class="content">{!! $service->description ?? '' !!}</div>
            <hr class="my-4">
            <a href="{{ route('tenant.public.services', $tenant->slug) }}" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i> Back to Services</a>
        </div>
    </div>
</main>
@endsection
