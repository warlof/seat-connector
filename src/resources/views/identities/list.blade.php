@extends('web::layouts.grids.12')

@section('title', trans_choice('seat-connector::seat.identities', 0))
@section('page_header', trans_choice('seat-connector::seat.identities', 0))

@section('full')

  @foreach($drivers->split(abs($drivers->count() / 3)) as $row)

    @foreach ($row as $driver => $metadata)

      <div class="col-md-3">
        <div class="small-box bg-gray">
          <div class="inner">
            <h4 class="text-uppercase">
              <strong>{{ $metadata['name'] }}</strong>
            </h4>
            <dl class="dl-horizontal">
              <dt>Name</dt>
              <dd>
                @if($identity = $identities->where('connector_type', $driver)->first())
                <i>{{ $identity->connector_name }}</i>
                @endif
              </dd>
              <dt>Unique ID</dt>
              <dd>
                @if($identity = $identities->where('connector_type', $driver)->first())
                <i>{{ $identity->unique_id }}</i>
                @endif
              </dd>
              <dt>Created on</dt>
              <dd>
                @if($identity = $identities->where('connector_type', $driver)->first())
                <i>{{ $identity->created_at }}</i>
                @endif
              </dd>
              <dt>Status</dt>
              <dd>
                @if($identity = $identities->where('connector_type', $driver)->first())
                <i class="text-green">registered</i>
                @else
                <i class="text-red">unregistered</i>
                @endif
              </dd>
            </dl>
          </div>
          @if(array_key_exists('icon', $metadata))
          <div class="icon">
            <i class="fa {{ $metadata['icon'] }}"></i>
          </div>
          @endif
          <a class="small-box-footer" href="#">
            <i class="fa fa-arrow-circle-right"></i>
            Join Server
          </a>
        </div>
      </div>

    @endforeach

  @endforeach

@stop