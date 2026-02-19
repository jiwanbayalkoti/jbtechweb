@extends('layouts.tenant')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $website ? $website->pages()->count() : 0 }}</h3>
                <p>Pages</p>
            </div>
            <div class="icon"><i class="fas fa-file"></i></div>
            <a href="{{ route('tenant.pages.index') }}" class="small-box-footer">Manage <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>1</h3>
                <p>Website</p>
            </div>
            <div class="icon"><i class="fas fa-globe"></i></div>
            <a href="{{ route('tenant.websites.index') }}" class="small-box-footer">Settings <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Welcome, {{ auth()->user()->name }}</h3>
    </div>
    <div class="card-body">
        <p>Manage your website content from the sidebar menu.</p>
        @if($website)
        <a href="{{ route('tenant.public.home', auth()->user()->tenant->slug) }}" class="btn btn-primary" target="_blank">
            <i class="fas fa-external-link-alt"></i> View Website
        </a>
        @endif
    </div>
</div>
@endsection
