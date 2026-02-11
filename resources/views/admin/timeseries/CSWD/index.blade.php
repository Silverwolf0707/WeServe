@extends('layouts.admin')

@can('CSWD-ANALYTICS')
    @section('content')
        @include('admin.timeseries.cswd.stl_decomposition')
        @include('admin.timeseries.cswd.statistics')     
    @endsection
@endcan

