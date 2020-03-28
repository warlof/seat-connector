@extends('web::layouts.grids.12')

@section('title', trans_choice('seat-connector::seat.identities', 0))
@section('page_header', trans_choice('seat-connector::seat.identities', 0))

@section('full')
  @if(config('seat-connector.drivers', []) == [])
    <div class="callout callout-info">
      <h4>Missing platforms</h4>
      <p>The administrator of this instance didn't configure any platform yet.<br/>Please come back later.</p>
    </div>
  @else
    @foreach($drivers->split(ceil($drivers->count() / 4)) as $row)

      <div class="row">

        @foreach ($row as $driver => $metadata)

          @include('seat-connector::identities.includes.card')

        @endforeach

      </div>

    @endforeach

    @yield('identity-modal')
  @endif

@stop