<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <!-- Favicon (if needed) -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('icon.svg') }}">

    <!-- Bootstrap 5 (recommended, replacing 4.1.3/4.2.1) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AdminLTE 3 Custom Overrides (your custom layout) -->
    <link href="{{ asset('css/adminltev3.css') }}" rel="stylesheet">

    <!-- Font Awesome (only keep latest v5) -->
    <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">

    <!-- iCheck Bootstrap for custom checkboxes/radios -->
    <link href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css" rel="stylesheet">

    <!-- DataTables (Bootstrap 5 version) -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css" rel="stylesheet">

    <!-- Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet">

    <!-- Flatpickr (modern datetime picker) -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

    <!-- Dropzone -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/6.0.0/dropzone.min.css" rel="stylesheet">

    <!-- Google Fonts (AdminLTE default) -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- Your Custom Styles -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    @yield('styles')
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden login-page">
    @yield('content')
    @yield('scripts')
</body>

</html>