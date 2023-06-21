<!DOCTYPE html>

<html lang="fa">
<head>
@include('layouts.partials.head')
</head>
<body class="hold-transition sidebar-mini">
<main class="wrapper">
    @include('layouts.partials.sidebar')
    <div class="content-wrapper">
        @yield('header')
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Footer -->
    <footer class="main-footer">
    @include('layouts.partials.footer' , ['text'=> 'امیر صولتی'])
    </footer>
    @include('layouts.partials.footer-script')
</main>
</body>
</html>
