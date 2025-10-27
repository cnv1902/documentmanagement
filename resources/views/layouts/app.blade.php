<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EduLib | Quản lý tài liệu')</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/image.png') }}" />
    
    <link rel="stylesheet" href="{{ asset('assets/css/backend-plugin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/backend.css?v=1.0.0') }}">
    
    <link rel="stylesheet" href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/remixicon/fonts/remixicon.css') }}">
    
    <!-- Viewer Plugin -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/doc-viewer/include/pdf/pdf.viewer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/doc-viewer/include/PPTXjs/css/pptxjs.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/doc-viewer/include/PPTXjs/css/nv.d3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/doc-viewer/include/SheetJS/handsontable.full.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/doc-viewer/include/verySimpleImageViewer/css/jquery.verySimpleImageViewer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/doc-viewer/include/officeToHtml/officeToHtml.css') }}">
    
    @stack('styles')
</head>
<body class="">
    <!-- loader Start -->
    <div id="loading">
        <div id="loading-center"></div>
    </div>
    <!-- loader END -->
    
    <!-- Wrapper Start -->
    <div class="wrapper">
        @include('partials.sidebar')
        @include('partials.navbar')
        
        <div class="content-page">
            @yield('content')
        </div>
    </div>
    <!-- Wrapper End-->
    
    @include('partials.footer')
    
    <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('assets/js/backend-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/customizer.js') }}"></script>
    <script src="{{ asset('assets/js/chart-custom.js') }}"></script>
    
    <!--PDF-->
    <script src="{{ asset('assets/vendor/doc-viewer/include/pdf/pdf.js') }}"></script>
    <!--Docs-->
    <script src="{{ asset('assets/vendor/doc-viewer/include/docx/jszip-utils.js') }}"></script>
    <script src="{{ asset('assets/vendor/doc-viewer/include/docx/mammoth.browser.min.js') }}"></script>
    <!--PPTX-->
    <script src="{{ asset('assets/vendor/doc-viewer/include/PPTXjs/js/filereader.js') }}"></script>
    <script src="{{ asset('assets/vendor/doc-viewer/include/PPTXjs/js/d3.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/doc-viewer/include/PPTXjs/js/nv.d3.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/doc-viewer/include/PPTXjs/js/pptxjs.js') }}"></script>
    <script src="{{ asset('assets/vendor/doc-viewer/include/PPTXjs/js/divs2slides.js') }}"></script>
    <!--All Spreadsheet -->
    <script src="{{ asset('assets/vendor/doc-viewer/include/SheetJS/handsontable.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/doc-viewer/include/SheetJS/xlsx.full.min.js') }}"></script>
    <!--Image viewer-->
    <script src="{{ asset('assets/vendor/doc-viewer/include/verySimpleImageViewer/js/jquery.verySimpleImageViewer.js') }}"></script>
    <!--officeToHtml-->
    <script src="{{ asset('assets/vendor/doc-viewer/include/officeToHtml/officeToHtml.js') }}"></script>
    <script src="{{ asset('assets/js/doc-viewer.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
