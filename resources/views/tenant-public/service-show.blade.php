@extends('tenant-public.layout')

@section('title', $service->title . ' - Services - ' . ($website->name ?? $tenant->name))
@section('meta_description', Str::limit(strip_tags($service->description ?? ''), 160))

@section('content')
<main>
    <div class="container">
        <div class="page-card">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('public.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('public.services') }}">Services</a></li>
                    <li class="breadcrumb-item active">{{ $service->title }}</li>
                </ol>
            </nav>
            <h1 class="fw-bold mb-2">{{ $service->title }}</h1>
            <p class="text-muted small mb-4"><i class="far fa-calendar-plus me-1"></i> {{ $service->created_at->format('M d, Y') }}</p>
            @if($service->icon ?? null)
            <p class="mb-4"><span class="text-primary" style="font-size: 3rem;"><i class="{{ $service->icon }}"></i></span></p>
            @endif
            <div class="content">{!! $service->description ?? '' !!}</div>

            @if(session('success'))
            <div class="alert alert-success mt-4">{{ session('success') }}</div>
            @endif

            @if($service->activePlans->isNotEmpty())
            <hr class="my-4">
            <h3 class="fw-bold mb-3">Choose a Plan</h3>
            <div class="row g-4 mb-4">
                @foreach($service->activePlans as $plan)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-custom h-100 p-4">
                        <h5 class="fw-bold mb-2">{{ $plan->name }}</h5>
                        <div class="h3 fw-bold text-primary mb-2">
                            {{ number_format((float) $plan->price, 2) }}
                            <small class="fs-6 text-muted">/ {{ str_replace('_', ' ', $plan->billing_cycle) }}</small>
                        </div>
                        @if($plan->description)
                        <p class="text-muted small">{{ $plan->description }}</p>
                        @endif
                        @if($plan->features)
                        <ul class="small text-muted ps-3">
                            @foreach($plan->features as $feature)
                            <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                        @endif
                        <button type="button" class="btn btn-primary mt-auto js-select-service-plan" data-plan-id="{{ $plan->id }}" data-plan-name="{{ $plan->name }}">
                            Subscribe / Buy Plan
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="card card-custom p-4" id="buyPlanForm">
                <h4 class="fw-bold mb-2">Buy / Subscribe Plan</h4>
                <div class="alert alert-info d-none" id="planSelectedAlert"></div>
                <p class="text-muted small">Selected plan: <span id="selectedPlanName">{{ $service->activePlans->first()->name }}</span></p>
                <form method="POST" action="{{ route('public.service.buy', $service->slug) }}">
                    @csrf
                    <input type="hidden" name="service_plan_id" id="service_plan_id" value="{{ $service->activePlans->first()->id }}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="3">{{ old('message') }}</textarea>
                        </div>
                    </div>
                    @if($errors->any())
                    <div class="alert alert-danger mt-3 mb-0">
                        @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                        @endforeach
                    </div>
                    @endif
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
            @endif

            <hr class="my-4">
            <a href="{{ route('public.services') }}" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i> Back to Services</a>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
function selectServicePlan(planId, planName, clickedButton) {
    var formCard = document.getElementById('buyPlanForm');
    var planInput = document.getElementById('service_plan_id');
    var planLabel = document.getElementById('selectedPlanName');
    var alertBox = document.getElementById('planSelectedAlert');
    var nameInput = formCard ? formCard.querySelector('input[name="name"]') : null;

    if (!formCard || !planInput || !planLabel) return;

    planInput.value = planId;
    planLabel.textContent = planName;

    if (alertBox) {
        alertBox.textContent = planName + ' selected. Please fill your details to create invoice.';
        alertBox.classList.remove('d-none');
    }

    formCard.classList.add('border', 'border-primary');
    setTimeout(function() {
        formCard.classList.remove('border', 'border-primary');
    }, 1800);

    if (clickedButton) {
        var originalText = clickedButton.textContent;
        clickedButton.textContent = 'Selected - Fill Form';
        setTimeout(function() {
            clickedButton.textContent = originalText;
        }, 1500);
    }

    formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });

    if (nameInput) {
        setTimeout(function() {
            nameInput.focus();
        }, 500);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.js-select-service-plan').forEach(function(button) {
        button.addEventListener('click', function() {
            selectServicePlan(this.dataset.planId, this.dataset.planName, this);
        });
    });
});
</script>
@endpush
