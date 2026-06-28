@extends('tenant-public.layout')

@section('title', $job->title . ' - Careers - ' . ($website->name ?? $tenant->name))
@section('meta_description', Str::limit(strip_tags($job->description ?? ''), 160))

@section('content')
<main>
    <div class="container">
        <div class="page-card">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('public.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('public.careers') }}">Careers</a></li>
                    <li class="breadcrumb-item active">{{ $job->title }}</li>
                </ol>
            </nav>
            <h1 class="fw-bold mb-3">{{ $job->title }}</h1>
            <p class="text-muted mb-4">
                @if($job->department)<span class="me-3"><i class="fas fa-building me-1"></i> {{ $job->department }}</span>@endif
                @if($job->location)<span class="me-3"><i class="fas fa-map-marker-alt me-1"></i> {{ $job->location }}</span>@endif
                @if($job->type)<span class="me-3"><i class="fas fa-briefcase me-1"></i> {{ $job->type }}</span>@endif
                @if($job->application_deadline)<span><i class="far fa-calendar me-1"></i> Apply by {{ $job->application_deadline->format('M d, Y') }}</span>@endif
                <span class="ms-2"><i class="far fa-calendar-plus me-1"></i> Posted {{ $job->created_at->format('M d, Y') }}</span>
            </p>
            <div class="content">
                @if($job->description)
                <h5 class="fw-bold mt-4">Description</h5>
                {!! $job->description !!}
                @endif
                @if($job->requirements)
                <h5 class="fw-bold mt-4">Requirements</h5>
                {!! $job->requirements !!}
                @endif
            </div>
            <hr class="my-4">
            <a href="{{ route('public.careers') }}" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i> Back to Careers</a>
        </div>
    </div>
</main>
@endsection
