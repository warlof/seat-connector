@extends('web::layouts.grids.3-9')

@section('title', trans_choice('web::seat.log', 0))
@section('page_header', trans_choice('web::seat.log', 0))

@section('left')
  <div class="box box-solid">
    <div class="box-header with-border">
      <i class="fa fa-question-circle"></i>
      <h3 class="box-title">{{ trans('seat-connector::seat.help') }}</h3>
    </div>
    <div class="box-body"></div>
  </div>
@stop

@section('right')
  <div class="box box-solid">
    <div class="box-header with-border">
      <i class="fa fa-list"></i>
      <h3 class="box-title">{{ trans('seat-connector::seat.journal') }}</h3>
    </div>
    <div class="box-body">
      {!! $dataTable->table() !!}
    </div>
  </div>
@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
