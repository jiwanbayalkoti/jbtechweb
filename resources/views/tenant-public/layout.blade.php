<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $website->name ?? $tenant->name)</title>
    <meta name="description" content="@yield('meta_description', $website->tagline ?? '')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: {{ $website->primary_color ?? '#6366f1' }};
            --primary-dark: {{ $website->secondary_color ?? '#4f46e5' }};
            --body-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgba(0,0,0,.07), 0 2px 4px -2px rgba(0,0,0,.05);
            --card-shadow-hover: 0 20px 25px -5px rgba(0,0,0,.1), 0 8px 10px -6px rgba(0,0,0,.05);
        }
        * { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        body { background: var(--body-bg); min-height: 100vh; display: flex; flex-direction: column; }
        .navbar {
            background: #fff !important;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
            padding: .75rem 0;
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.35rem;
            color: #0f172a !important;
        }
        .nav-link {
            font-weight: 500;
            color: #475569 !important;
            padding: .5rem 1rem !important;
            border-radius: 8px;
            transition: all .2s;
        }
        .nav-link:hover { color: var(--primary) !important; background: rgba(99,102,241,.08); }
        .btn-primary {
            background: var(--primary) !important;
            border: none !important;
            padding: .6rem 1.25rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all .2s;
        }
        .btn-primary:hover { background: var(--primary-dark) !important; transform: translateY(-1px); }
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            padding: 4rem 0;
            border-radius: 0 0 2rem 2rem;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 60%;
            height: 200%;
            background: rgba(255,255,255,.08);
            border-radius: 50%;
        }
        .hero h1 { font-weight: 800; font-size: 2.5rem; }
        .hero .lead { opacity: .9; font-size: 1.1rem; }
        main { flex: 1; padding: 3rem 0; }
        .content h1, .content h2, .content h3, .content h4, .content h5, .content h6 { margin-top: 1.25rem; margin-bottom: .75rem; font-weight: 700; color: #0f172a; }
        .content h1 { font-size: 1.75rem; }
        .content h2 { font-size: 1.5rem; }
        .content h3 { font-size: 1.25rem; }
        .content p { margin-bottom: 1rem; line-height: 1.8; color: #334155; }
        .content ul, .content ol { margin-bottom: 1rem; padding-left: 1.5rem; color: #334155; }
        .content li { margin-bottom: .35rem; }
        .content a { color: var(--primary); text-decoration: none; }
        .content a:hover { text-decoration: underline; }
        .content.text-muted a { color: var(--primary); }
        .content strong { font-weight: 700; color: #0f172a; }
        .content em { font-style: italic; }
        .content blockquote { border-left: 4px solid var(--primary); padding-left: 1rem; margin: 1.25rem 0; color: #475569; font-style: italic; }
        .content img { max-width: 100%; height: auto; border-radius: 12px; margin: .75rem 0; }
        .content table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        .content table th, .content table td { border: 1px solid #e2e8f0; padding: .5rem .75rem; text-align: left; }
        .content table th { background: #f8fafc; font-weight: 600; }
        .content hr { margin: 1.5rem 0; border: 0; border-top: 1px solid #e2e8f0; }
        .content-excerpt { display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 4; overflow: hidden; font-size: 0.9em; }
        .content-excerpt.content { line-height: 1.6; }
        .card-custom {
            background: #fff;
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: box-shadow .3s;
        }
        .card-custom:hover { box-shadow: var(--card-shadow-hover); }
        .footer {
            background: #0f172a;
            color: rgba(255,255,255,.7);
            padding: 3rem 0 1.5rem;
            margin-top: auto;
        }
        .footer a { color: rgba(255,255,255,.8); text-decoration: none; }
        .footer a:hover { color: #fff; }
        .page-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            padding: 2.5rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('public.home') }}">
                {{ $website->name ?? $tenant->name }}
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.home') }}">Home</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('public.blog.index') }}">Blog</a></li>
                    @if(isset($services) && $services->isNotEmpty())
                    <li class="nav-item"><a class="nav-link" href="{{ route('public.services') }}">Services</a></li>
                    @endif
                    <li class="nav-item"><a class="nav-link" href="{{ route('public.portfolio') }}">Portfolio</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('public.testimonials') }}">Testimonials</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('public.careers') }}">Careers</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('public.media') }}">Media</a></li>
                    @if($headerMenu ?? null)
                        @foreach($headerMenu->items as $item)
                        @php
                            $url = $item->url ?? '';
                            if ($url && !str_starts_with($url, 'http') && !str_starts_with($url, '/s/')) {
                                $url = trim($url, '/');
                                $url = $url ? route('public.page', $url) : route('public.home');
                            }
                        @endphp
                        <li class="nav-item"><a class="nav-link" href="{{ $url ?: '#' }}" target="{{ $item->target ?? '_self' }}">{{ $item->title }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <p class="mb-2 fw-semibold text-white">{{ $website->name ?? $tenant->name }}</p>
                    <p class="mb-4 opacity-75">{{ $website->tagline ?? '' }}</p>
                    <p class="small mb-0">&copy; {{ date('Y') }} {{ $tenant->name }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
