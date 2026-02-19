@extends('layouts.tenant')

@section('title', 'Website Settings')
@section('page-title', 'Website Settings')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Website Settings</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @forelse($websites as $website)
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4>{{ $website->name }}</h4>
                <p class="text-muted mb-0">{{ $website->tagline ?? 'No tagline set' }}</p>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('tenant.websites.edit', $website) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Settings
                </a>
            </div>
        </div>
        <hr>
        @empty
        <p class="mb-0">No website found. Contact your administrator.</p>
        @endforelse
    </div>
    @if($websites->hasPages())
    <div class="card-footer">{{ $websites->links() }}</div>
    @endif
</div>
@endsection
