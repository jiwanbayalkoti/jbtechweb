@extends('tenant-public.layout')

@section('title', 'Testimonials - ' . ($website->name ?? $tenant->name))
@section('meta_description', 'What our clients say - ' . ($website->name ?? $tenant->name))

@section('content')
<main>
    <div class="container">
        <div class="page-card mb-4">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('public.home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Testimonials</li>
                </ol>
            </nav>
            <h1 class="fw-bold mb-2">What Our Clients Say</h1>
            <p class="text-muted">Real feedback from people we've worked with.</p>
        </div>

        @if($testimonials->isNotEmpty())
        <div class="row g-4">
            @foreach($testimonials as $t)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('public.testimonial.show', $t->id) }}" class="card card-custom p-4 h-100 text-decoration-none text-dark d-block">
                    @if(($t->rating ?? 0) > 0)
                    <div class="text-warning mb-3">
                        @for($i = 1; $i <= 5; $i++) <i class="fas fa-star{{ $i <= $t->rating ? '' : '-o' }}"></i> @endfor
                    </div>
                    @endif
                    <div class="content content-excerpt mb-3">
                    {!! $t->content !!}
                </div>
                    <small class="text-muted d-block mb-3"><i class="far fa-calendar-plus me-1"></i> {{ $t->created_at->format('M d, Y') }}</small>
                    <div class="d-flex align-items-center">
                        @if($t->client_avatar)
                        <img src="{{ asset('storage/' . $t->client_avatar) }}" alt="" class="rounded-circle me-3" width="48" height="48" style="object-fit: cover;">
                        @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">{{ strtoupper(substr($t->client_name ?? 'A', 0, 1)) }}</div>
                        @endif
                        <div>
                            <strong>{{ $t->client_name }}</strong>
                            @if($t->client_title || $t->client_company)
                            <br><small class="text-muted">{{ $t->client_title }}{{ $t->client_title && $t->client_company ? ' at ' : '' }}{{ $t->client_company }}</small>
                            @endif
                        </div>
                    </div>
                    <p class="mt-3 mb-0 small text-primary fw-semibold">Read full testimonial <i class="fas fa-arrow-right ms-1"></i></p>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="page-card text-center py-5">
            <p class="text-muted mb-0">No testimonials yet.</p>
        </div>
        @endif

        <div class="text-center mt-5">
            <a href="{{ route('public.home') }}" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i> Back to Home</a>
        </div>
    </div>
</main>
@endsection
