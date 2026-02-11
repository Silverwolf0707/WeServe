@extends('layouts.admin')
@can('BUDGET-ANALYTICS')
    

@section('content')
      @include('admin.timeseries.budget.stl_decomposition')
      @include('admin.timeseries.budget.statistics')
      
@endsection  
@endcan    

