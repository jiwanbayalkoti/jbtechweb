@foreach($media as $item)
@php
    $m = $item->cover;
@endphp
<div class="col-6 col-md-4 col-lg-3 media-item">
    @if($item->is_image)
    <a href="{{ route('public.media.show', $m->id) }}" class="d-block text-decoration-none js-media-popup" data-media-id="{{ $m->id }}">
        <div class="card card-custom overflow-hidden p-0" style="border-radius: 16px;">
            <div class="position-relative">
                <img src="{{ asset('storage/' . $m->file_path) }}" alt="{{ $m->alt_text ?? $item->title }}" class="img-fluid w-100" style="height: 200px; object-fit: cover;">
                @if($item->type === 'album')
                <span class="badge bg-dark position-absolute top-0 end-0 m-2"><i class="fas fa-images me-1"></i> {{ $item->count }}</span>
                @endif
            </div>
            <div class="p-2 small">
                <span class="text-truncate d-block fw-semibold" title="{{ $item->title }}">{{ $item->title }}</span>
                @if($item->description)
                <span class="text-muted text-truncate d-block" title="{{ $item->description }}">{{ $item->description }}</span>
                @endif
                <span class="text-muted"><i class="far fa-calendar-plus me-1"></i> {{ $m->created_at->format('M d, Y') }}</span>
            </div>
        </div>
    </a>
    @else
    <a href="{{ asset('storage/' . $m->file_path) }}" download="{{ $m->file_name }}" class="d-block text-decoration-none text-dark">
        <div class="card card-custom p-3 h-100 text-center" style="border-radius: 16px;">
            <i class="fas fa-file-alt fa-3x text-primary mb-2"></i>
            <div class="small text-truncate fw-semibold" title="{{ $item->title }}">{{ $item->title }}</div>
            @if($item->description)
            <small class="text-muted text-truncate d-block" title="{{ $item->description }}">{{ $item->description }}</small>
            @endif
            <small class="text-muted d-block"><i class="far fa-calendar-plus me-1"></i> {{ $m->created_at->format('M d, Y') }}</small>
            @if($m->file_size)
            <small class="text-muted">{{ number_format($m->file_size / 1024, 1) }} KB</small>
            @endif
        </div>
    </a>
    @endif
</div>
@endforeach
