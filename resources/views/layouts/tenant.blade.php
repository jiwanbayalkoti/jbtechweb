<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ auth()->user()->tenant->name ?? 'Tenant' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css">
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('tenant.partials.navbar')
        @include('tenant.partials.sidebar')

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @hasSection('breadcrumb')
                                    {!! $__env->yieldContent('breadcrumb') !!}
                                @else
                                    <li class="breadcrumb-item active">Dashboard</li>
                                @endif
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif
                    @yield('content')
                </div>
            </section>
        </div>
        @include('tenant.partials.footer')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ === 'undefined') return;
        function initRichEditors(container) {
            var el = container ? $(container).find('.rich-editor') : $('.rich-editor');
            el.each(function() {
                var $ta = $(this);
                if ($ta.data('summernote')) return;
                $ta.summernote({
                    height: 220,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'picture', 'table']],
                        ['view', ['fullscreen', 'codeview']]
                    ],
                    callbacks: {
                        onImageUpload: function(files) {
                            var $ed = $(this);
                            var data = new FormData();
                            data.append('file', files[0]);
                            data.append('_token', $('meta[name="csrf-token"]').attr('content'));
                            $.ajax({
                                url: '{{ url("tenant/editor/upload-image") }}',
                                method: 'POST',
                                data: data,
                                processData: false,
                                contentType: false,
                                success: function(res) {
                                    $ed.summernote('insertImage', res.url);
                                }
                            });
                        }
                    }
                });
            });
        }
        function destroyRichEditors(container) {
            var el = container ? $(container).find('.rich-editor') : $('.rich-editor');
            el.each(function() { $(this).summernote('destroy'); });
        }
        initRichEditors();
        $(document).on('shown.bs.modal', '.modal', function() {
            initRichEditors(this);
        });
        $(document).on('hidden.bs.modal', '.modal', function() {
            destroyRichEditors(this);
        });
        window.initRichEditors = initRichEditors;
        window.destroyRichEditors = destroyRichEditors;
    });
    </script>
    @stack('scripts')
</body>
</html>
