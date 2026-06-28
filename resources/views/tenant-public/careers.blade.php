@extends('tenant-public.layout')

@section('title', 'Careers - ' . ($website->name ?? $tenant->name))
@section('meta_description', 'Join our team - ' . ($website->name ?? $tenant->name))

@section('content')
<main>
    <div class="container">
        <div class="page-card mb-4">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('public.home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Careers</li>
                </ol>
            </nav>
            <h1 class="fw-bold mb-2">Careers</h1>
            <p class="text-muted">Open positions. Join our team.</p>
        </div>

        @if($careers->isNotEmpty())
        <div class="row g-4">
            @foreach($careers as $job)
            <div class="col-12">
                <a href="{{ route('public.career.show', $job->slug) }}" class="card card-custom text-decoration-none text-dark p-4 d-block">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="fw-bold mb-2">{{ $job->title }}</h5>
                            <p class="text-muted small mb-0">
                                @if($job->department)<span class="me-3"><i class="fas fa-building me-1"></i> {{ $job->department }}</span>@endif
                                @if($job->location)<span class="me-3"><i class="fas fa-map-marker-alt me-1"></i> {{ $job->location }}</span>@endif
                                @if($job->type)<span><i class="fas fa-briefcase me-1"></i> {{ $job->type }}</span>@endif
                            </p>
                            @if($job->application_deadline)
                            <small class="text-muted">Apply by {{ $job->application_deadline->format('M d, Y') }}</small>
                            @endif
                            <br><small class="text-muted"><i class="far fa-calendar-plus me-1"></i> Posted {{ $job->created_at->format('M d, Y') }}</small>
                        </div>
                        <div class="col-md-4 text-md-end mt-2 mt-md-0">
                            <span class="btn btn-primary btn-sm">View details <i class="fas fa-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="page-card text-center py-5">
            <p class="text-muted mb-0">No open positions at the moment. Check back later!</p>
        </div>
        @endif

        <div class="text-center mt-5">
            <a href="{{ route('public.home') }}" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i> Back to Home</a>
        </div>
    </div>
</main>
@endsection
