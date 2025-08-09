@extends('layouts.admin')

@can('CSWD-ANALYTICS')
    @section('content')
        @include('admin.timeseries.CSWD.stl_decomposition')
        @include('admin.timeseries.CSWD.statistics')     
    @endsection
@endcan

