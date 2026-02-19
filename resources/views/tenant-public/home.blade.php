@extends('tenant-public.layout')

@section('title', $website->name ?? $tenant->name)
@section('meta_description', $website->tagline ?? '')

@push('styles')
<style>
    .home-hero {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 50%, #4338ca 100%);
        color: #fff;
        padding: 5rem 0 6rem;
        position: relative;
        overflow: hidden;
        border-radius: 0 0 3rem 3rem;
    }
    @if($website->banner_image ?? null)
    .home-hero.has-banner {
        position: relative;
        min-height: 420px;
        background: url('{{ asset('storage/' . $website->banner_image) }}') center / cover no-repeat;
    }
    .home-hero.has-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, rgba(15,23,42,.92) 0%, rgba(30,41,59,.7) 45%, rgba(51,65,85,.35) 70%, transparent 100%);
        border-radius: 0 0 3rem 3rem;
        pointer-events: none;
    }
    .home-hero.has-banner::after { display: none; }
    @media (max-width: 991.98px) {
        .home-hero.has-banner { min-height: 380px; }
    }
    @endif
    .home-hero::before {
        content: '';
        position: absolute;
        top: -80%;
        right: -30%;
        width: 80%;
        height: 260%;
        background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .home-hero::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: -20%;
        width: 50%;
        height: 120%;
        background: rgba(255,255,255,.06);
        border-radius: 50%;
        pointer-events: none;
    }
    .home-hero .container { position: relative; z-index: 1; }
    .home-hero h1 {
        font-weight: 800;
        font-size: clamp(2rem, 5vw, 3.25rem);
        letter-spacing: -0.02em;
        line-height: 1.2;
        text-shadow: 0 2px 20px rgba(0,0,0,.15);
    }
    .home-hero .lead {
        font-size: 1.2rem;
        opacity: .95;
        max-width: 28rem;
    }
    .home-intro {
        margin-top: -3rem;
        position: relative;
        z-index: 2;
    }
    .home-intro .card-wrap {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,.12), 0 0 0 1px rgba(0,0,0,.04);
        padding: 2.5rem 3rem;
    }
    .home-section {
        padding: 4rem 0;
    }
    .home-section:nth-child(even) { background: linear-gradient(180deg, #f8fafc 0%, #fff 100%); }
    .section-label {
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .15em;
        text-transform: uppercase;
        color: var(--primary);
        margin-bottom: .5rem;
    }
    .section-title {
        font-weight: 800;
        font-size: 1.75rem;
        color: #0f172a;
        margin-bottom: 2rem;
    }
    .home-service-card {
        background: #fff;
        border-radius: 20px;
        padding: 2rem;
        height: 100%;
        border: 1px solid rgba(0,0,0,.06);
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .home-service-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -15px rgba(0,0,0,.15);
        border-color: transparent;
    }
    .home-service-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1.25rem;
    }
    .home-blog-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        height: 100%;
        border: 1px solid rgba(0,0,0,.06);
        transition: transform .25s ease, box-shadow .25s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .home-blog-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -15px rgba(0,0,0,.15); color: inherit; }
    .home-blog-card .img-wrap {
        height: 160px;
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 2.5rem;
    }
    .home-blog-card .img-wrap img { width: 100%; height: 100%; object-fit: cover; }
    .home-blog-card .body { padding: 1.5rem; }
    .home-blog-card .date { font-size: .8rem; color: var(--primary); font-weight: 600; }
    .home-portfolio-card {
        border-radius: 20px;
        overflow: hidden;
        height: 100%;
        background: #fff;
        border: 1px solid rgba(0,0,0,.06);
        transition: transform .25s ease, box-shadow .25s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .home-portfolio-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -15px rgba(0,0,0,.15); color: inherit; }
    .home-portfolio-card .img-wrap {
        height: 220px;
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 3rem;
    }
    .home-portfolio-card .img-wrap img { width: 100%; height: 100%; object-fit: cover; }
    .home-portfolio-card .body { padding: 1.5rem; }
    .home-testimonial-card {
        background: #fff;
        border-radius: 20px;
        padding: 2rem;
        height: 100%;
        border: 1px solid rgba(0,0,0,.06);
        position: relative;
    }
    .home-testimonial-card::before {
        content: '"';
        position: absolute;
        top: 1rem;
        left: 1.5rem;
        font-size: 3rem;
        font-weight: 800;
        color: var(--primary);
        opacity: .2;
        line-height: 1;
    }
    .home-testimonial-card .stars { color: #f59e0b; }
    .home-testimonial-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
    }
    .home-career-row {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        border: 1px solid rgba(0,0,0,.06);
        transition: all .25s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .home-career-row:hover {
        border-color: var(--primary);
        box-shadow: 0 10px 30px -10px rgba(0,0,0,.1);
        color: inherit;
    }
    .home-explore-link {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        border: 1px solid rgba(0,0,0,.06);
        transition: all .25s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        gap: 1rem;
        height: 100%;
    }
    .home-explore-link:hover { border-color: var(--primary); color: inherit; box-shadow: 0 10px 30px -10px rgba(0,0,0,.08); }
    .home-explore-link .icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(99,102,241,.1);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-outline-primary-home {
        border: 2px solid rgba(255,255,255,.8);
        color: #fff;
        background: transparent;
        font-weight: 600;
        padding: .6rem 1.5rem;
        border-radius: 12px;
        transition: all .25s ease;
    }
    .btn-outline-primary-home:hover { background: #fff; color: var(--primary); border-color: #fff; }
</style>
@endpush

@section('content')
<div class="home-hero {{ ($website->banner_image ?? null) ? 'has-banner' : '' }}">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 col-xl-6">
                <h1 class="mb-3">{{ $page?->title ?? $website->name ?? 'Welcome' }}</h1>
                @if($website && ($website->tagline ?? null))
                <p class="lead mb-4">{{ $website->tagline }}</p>
                @endif
                @if(($services ?? collect())->isNotEmpty())
                <a href="{{ route('tenant.public.services', $tenant->slug) }}" class="btn btn-outline-primary-home">Our Services</a>
                @endif
            </div>
        </div>
    </div>
</div>

<main>
    

    @if(($services ?? collect())->isNotEmpty())
    <section class="home-section" id="services">
        <div class="container">
            <p class="section-label">What we do</p>
            <h2 class="section-title">Our Services</h2>
            <div class="row g-4 mb-5">
                @foreach($services as $s)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tenant.public.services', $tenant->slug) }}" class="text-decoration-none text-dark">
                        <div class="home-service-card">
                            <div class="home-service-icon">
                                @if($s->icon)<i class="{{ $s->icon }}"></i>@else<i class="fas fa-cog"></i>@endif
                            </div>
                            <h5 class="fw-bold mb-2" style="color: #0f172a;">{{ $s->title }}</h5>
                            <div class="content content-excerpt text-muted small mb-0" style="line-height: 1.6;">{!! $s->description ?? '' !!}</div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="text-center">
                <a href="{{ route('tenant.public.services', $tenant->slug) }}" class="btn btn-primary px-4 py-2">View All Services</a>
            </div>
        </div>
    </section>
    @endif

    @if(($blogs ?? collect())->isNotEmpty())
    <section class="home-section" id="blog">
        <div class="container">
            <p class="section-label">Latest updates</p>
            <h2 class="section-title">From the Blog</h2>
            <div class="row g-4 mb-5">
                @foreach($blogs as $post)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tenant.public.blog', [$tenant->slug, $post->slug]) }}" class="home-blog-card">
                        <div class="img-wrap">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div class="body">
                            <p class="date mb-2">{{ $post->published_at?->format('M d, Y') }}</p>
                            <h5 class="fw-bold mb-2" style="color: #0f172a;">{{ $post->title }}</h5>
                            <div class="content content-excerpt text-muted small mb-0" style="line-height: 1.6;">{!! $post->content ?? '' !!}</div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="text-center">
                <a href="{{ route('tenant.public.blog.index', $tenant->slug) }}" class="btn btn-primary px-4 py-2">View All Posts</a>
            </div>
        </div>
    </section>
    @endif

    @if(($portfolios ?? collect())->isNotEmpty())
    <section class="home-section" id="portfolio">
        <div class="container">
            <p class="section-label">Our work</p>
            <h2 class="section-title">Portfolio</h2>
            <div class="row g-4 mb-5">
                @foreach($portfolios as $item)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tenant.public.portfolio.show', [$tenant->slug, $item->slug]) }}" class="home-portfolio-card">
                        <div class="img-wrap">
                            @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}">
                            @else
                            <i class="fas fa-image"></i>
                            @endif
                        </div>
                        <div class="body">
                            @if($item->category)<span class="badge rounded-pill mb-2" style="background: rgba(99,102,241,.15); color: var(--primary);">{{ $item->category }}</span>@endif
                            <h5 class="fw-bold mb-0" style="color: #0f172a;">{{ $item->title }}</h5>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="text-center">
                <a href="{{ route('tenant.public.portfolio', $tenant->slug) }}" class="btn btn-primary px-4 py-2">View All Portfolio</a>
            </div>
        </div>
    </section>
    @endif

    @if(($testimonials ?? collect())->isNotEmpty())
    <section class="home-section" id="testimonials">
        <div class="container">
            <p class="section-label">Kind words</p>
            <h2 class="section-title">What Clients Say</h2>
            <div class="row g-4 mb-5">
                @foreach($testimonials as $t)
                <div class="col-md-6 col-lg-4">
                    <div class="home-testimonial-card">
                        @if(($t->rating ?? 0) > 0)
                        <div class="stars mb-2">@for($i = 1; $i <= 5; $i++) <i class="{{ $i <= $t->rating ? 'fas fa-star' : 'far fa-star' }}"></i> @endfor</div>
                        @endif
                        <div class="content content-excerpt small mb-4" style="line-height: 1.7; color: #475569;">{!! $t->content !!}</div>
                        <div class="d-flex align-items-center gap-3">
                            @if($t->client_avatar)
                            <img src="{{ asset('storage/' . $t->client_avatar) }}" alt="" class="rounded-circle" width="48" height="48" style="object-fit: cover;">
                            @else
                            <div class="home-testimonial-avatar">{{ strtoupper(substr($t->client_name ?? 'A', 0, 1)) }}</div>
                            @endif
                            <div>
                                <strong style="color: #0f172a;">{{ $t->client_name }}</strong>
                                @if($t->client_title || $t->client_company)
                                <br><small class="text-muted">{{ $t->client_title }}{{ $t->client_title && $t->client_company ? ' · ' : '' }}{{ $t->client_company }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center">
                <a href="{{ route('tenant.public.testimonials', $tenant->slug) }}" class="btn btn-primary px-4 py-2">All Testimonials</a>
            </div>
        </div>
    </section>
    @endif

    @if(($careers ?? collect())->isNotEmpty())
    <section class="home-section" id="careers">
        <div class="container">
            <p class="section-label">Join us</p>
            <h2 class="section-title">We're Hiring</h2>
            <div class="row g-3 mb-4">
                @foreach($careers as $job)
                <div class="col-12">
                    <a href="{{ route('tenant.public.career.show', [$tenant->slug, $job->slug]) }}" class="home-career-row">
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #0f172a;">{{ $job->title }}</h6>
                            <small class="text-muted">{{ $job->department ?? '' }}{{ $job->department && $job->location ? ' · ' : '' }}{{ $job->location ?? '' }}</small>
                        </div>
                        <span class="btn btn-primary btn-sm">View & Apply</span>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="text-center">
                <a href="{{ route('tenant.public.careers', $tenant->slug) }}" class="btn btn-primary px-4 py-2">All Open Positions</a>
            </div>
        </div>
    </section>
    @endif

    @if(($publishedPages ?? collect())->isNotEmpty())
    <section class="home-section">
        <div class="container">
            <p class="section-label">Explore</p>
            <h2 class="section-title">More to Read</h2>
            <div class="row g-3">
                @foreach($publishedPages->take(3) as $p)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tenant.public.page', [$tenant->slug, $p->slug]) }}" class="home-explore-link">
                        <div class="icon-wrap"><i class="fas fa-file-alt"></i></div>
                        <div>
                            <h6 class="fw-bold mb-0" style="color: #0f172a;">{{ $p->title }}</h6>
                            <small class="text-muted">Read more</small>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</main>
@endsection
