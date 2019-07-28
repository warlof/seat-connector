@extends('web::layouts.grids.12')

@section('title', trans_choice('seat-connector::seat.identities', 0))
@section('page_header', trans_choice('seat-connector::seat.identities', 0))

@section('full')

  @foreach($drivers->split(abs($drivers->count() / 3)) as $row)

    @foreach ($row as $driver => $metadata)

      @include('seat-connector::identities.includes.card')

    @endforeach

  @endforeach

@stop