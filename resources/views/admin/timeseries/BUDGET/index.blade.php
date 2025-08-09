@extends('layouts.admin')
@can('BUDGET-ANALYTICS')
    

@section('content')
      @include('admin.timeseries.BUDGET.stl_decomposition')
      @include('admin.timeseries.BUDGET.statistics')
      
@endsection  
@endcan    

