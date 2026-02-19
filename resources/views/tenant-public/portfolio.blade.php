@extends('tenant-public.layout')

@section('title', 'Portfolio - ' . ($website->name ?? $tenant->name))
@section('meta_description', 'Our work and projects - ' . ($website->name ?? $tenant->name))

@section('content')
<main>
    <div class="container">
        <div class="page-card mb-4">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('tenant.public.home', $tenant->slug) }}">Home</a></li>
                    <li class="breadcrumb-item active">Portfolio</li>
                </ol>
            </nav>
            <h1 class="fw-bold mb-2">Our Portfolio</h1>
            <p class="text-muted">Projects we're proud of.</p>
        </div>

        @if($portfolios->isNotEmpty())
        <div class="row g-4">
            @foreach($portfolios as $item)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('tenant.public.portfolio.show', [$tenant->slug, $item->slug]) }}" class="card card-custom text-decoration-none text-dark overflow-hidden h-100">
                    @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->title }}" style="height: 200px; object-fit: cover;">
                    @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;"><i class="fas fa-image fa-3x text-muted"></i></div>
                    @endif
                    <div class="card-body">
                        <h5 class="fw-bold mb-2">{{ $item->title }}</h5>
                        @if($item->category)<span class="badge bg-primary mb-2">{{ $item->category }}</span>@endif
                        <div class="content content-excerpt small text-muted mb-2">
                        {!! $item->description ?? '' !!}
                    </div>
                        <small class="text-muted"><i class="far fa-calendar-plus me-1"></i> {{ $item->created_at->format('M d, Y') }}</small>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="page-card text-center py-5">
            <p class="text-muted mb-0">No portfolio items yet.</p>
        </div>
        @endif

        <div class="text-center mt-5">
            <a href="{{ route('tenant.public.home', $tenant->slug) }}" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i> Back to Home</a>
        </div>
    </div>
</main>
@endsection
