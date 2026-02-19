@extends('tenant-public.layout')

@section('title', 'Services - ' . ($website->name ?? $tenant->name))
@section('meta_description', $website->tagline ?? 'Our Services')

@section('content')
<main>
    <div class="container">
        <div class="page-card mb-4">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('tenant.public.home', $tenant->slug) }}">Home</a></li>
                    <li class="breadcrumb-item active">Services</li>
                </ol>
            </nav>
            <h1 class="fw-bold mb-2">Our Services</h1>
            <p class="text-muted">What we offer to help you succeed.</p>
        </div>

        @if($services->isNotEmpty())
        <div class="row g-4">
            @foreach($services as $s)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('tenant.public.service.show', [$tenant->slug, $s->slug]) }}" class="card card-custom p-4 h-100 text-decoration-none text-dark d-block">
                    @if($s->icon ?? null)
                    <span class="text-primary mb-3 d-inline-block" style="font-size: 2.5rem;"><i class="{{ $s->icon }}"></i></span>
                    @else
                    <span class="text-primary mb-3 d-inline-block" style="font-size: 2.5rem;"><i class="fas fa-cog"></i></span>
                    @endif
                    <h5 class="fw-bold mb-3">{{ $s->title }}</h5>
                    <p class="text-muted small mb-2"><i class="far fa-calendar-plus me-1"></i> {{ $s->created_at->format('M d, Y') }}</p>
                    <div class="content content-excerpt small text-muted">
                        {!! $s->description ?? '' !!}
                    </div>
                    <p class="mt-3 mb-0 small text-primary fw-semibold">View details <i class="fas fa-arrow-right ms-1"></i></p>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="page-card text-center py-5">
            <p class="text-muted mb-0">No services added yet.</p>
        </div>
        @endif

        <div class="text-center mt-5">
            <a href="{{ route('tenant.public.home', $tenant->slug) }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i> Back to Home
            </a>
        </div>
    </div>
</main>
@endsection
