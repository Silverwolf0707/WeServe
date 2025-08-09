@extends('layouts.admin')
@can('ACCOUNTING-ANALYTICS')
    

@section('content')
      @include('admin.timeseries.ACCOUNTING.stl_decomposition')
      @include('admin.timeseries.ACCOUNTING.statistics')
      
@endsection  
@endcan    

