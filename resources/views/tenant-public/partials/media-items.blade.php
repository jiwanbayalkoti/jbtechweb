@foreach($media as $m)
<div class="col-6 col-md-4 col-lg-3 media-item">
    @if(in_array(strtolower($m->file_type), ['jpg','jpeg','png','gif','webp','svg']))
    <a href="{{ asset('storage/' . $m->file_path) }}" target="_blank" rel="noopener" class="d-block text-decoration-none">
        <div class="card card-custom overflow-hidden p-0" style="border-radius: 16px;">
            <img src="{{ asset('storage/' . $m->file_path) }}" alt="{{ $m->alt_text ?? $m->title ?? $m->file_name }}" class="img-fluid w-100" style="height: 200px; object-fit: cover;">
            <div class="p-2 small">
                <span class="text-truncate d-block" title="{{ $m->title ?? $m->file_name }}">{{ $m->title ?? $m->file_name }}</span>
                <span class="text-muted"><i class="far fa-calendar-plus me-1"></i> {{ $m->created_at->format('M d, Y') }}</span>
            </div>
        </div>
    </a>
    @else
    <a href="{{ asset('storage/' . $m->file_path) }}" download="{{ $m->file_name }}" class="d-block text-decoration-none text-dark">
        <div class="card card-custom p-3 h-100 text-center" style="border-radius: 16px;">
            <i class="fas fa-file-alt fa-3x text-primary mb-2"></i>
            <div class="small text-truncate" title="{{ $m->title ?? $m->file_name }}">{{ $m->title ?? $m->file_name }}</div>
            <small class="text-muted d-block"><i class="far fa-calendar-plus me-1"></i> {{ $m->created_at->format('M d, Y') }}</small>
            @if($m->file_size)
            <small class="text-muted">{{ number_format($m->file_size / 1024, 1) }} KB</small>
            @endif
        </div>
    </a>
    @endif
</div>
@endforeach
