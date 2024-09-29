<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet">
  </head>
  <body>
    <div class="container-fluid">
      @yield('content')
    </div>
    <script src="{{ asset('build/js/bootstrap.bundle.min.js') }}"></script>
  </body>
</html>
