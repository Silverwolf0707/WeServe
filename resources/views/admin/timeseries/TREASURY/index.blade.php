@extends('layouts.admin')
@can('TREASURY-ANALYTICS')
    

@section('content')
      @include('admin.timeseries.TREASURY.stl_decomposition')
      @include('admin.timeseries.TREASURY.statistics')
      
@endsection  
@endcan    

