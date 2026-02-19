@extends('tenant-public.layout')

@section('title', ($testimonial->client_name ?? 'Testimonial') . ' - Testimonials - ' . ($website->name ?? $tenant->name))
@section('meta_description', Str::limit(strip_tags($testimonial->content ?? ''), 160))

@section('content')
<main>
    <div class="container">
        <div class="page-card">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('tenant.public.home', $tenant->slug) }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tenant.public.testimonials', $tenant->slug) }}">Testimonials</a></li>
                    <li class="breadcrumb-item active">{{ $testimonial->client_name ?? 'Testimonial' }}</li>
                </ol>
            </nav>
            <div class="d-flex align-items-start gap-4 flex-wrap">
                @if($testimonial->client_avatar)
                <img src="{{ asset('storage/' . $testimonial->client_avatar) }}" alt="{{ $testimonial->client_name }}" class="rounded-circle" width="80" height="80" style="object-fit: cover;">
                @else
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 80px; height: 80px; font-size: 2rem;">{{ strtoupper(substr($testimonial->client_name ?? 'A', 0, 1)) }}</div>
                @endif
                <div class="flex-grow-1">
                    <h1 class="fw-bold mb-2">{{ $testimonial->client_name }}</h1>
                    @if($testimonial->client_title || $testimonial->client_company)
                    <p class="text-muted mb-3">{{ $testimonial->client_title }}{{ $testimonial->client_title && $testimonial->client_company ? ' at ' : '' }}{{ $testimonial->client_company }}</p>
                    @endif
                    @if(($testimonial->rating ?? 0) > 0)
                    <div class="text-warning mb-3">@for($i = 1; $i <= 5; $i++) <i class="fas fa-star{{ $i <= $testimonial->rating ? '' : '-o' }}"></i> @endfor</div>
                    @endif
                    <small class="text-muted d-block mb-3"><i class="far fa-calendar-plus me-1"></i> {{ $testimonial->created_at->format('M d, Y') }}</small>
                </div>
            </div>
            <div class="content mt-4">
                {!! $testimonial->content !!}
            </div>
            <hr class="my-4">
            <a href="{{ route('tenant.public.testimonials', $tenant->slug) }}" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i> Back to Testimonials</a>
        </div>
    </div>
</main>
@endsection
