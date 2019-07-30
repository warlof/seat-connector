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
          @if($identities->where('connector_type', $driver)->isNotEmpty())
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
    <a class="small-box-footer" href="{{ route(sprintf('seat-connector.drivers.%s.registration', $driver)) }}" target="_blank">
      <i class="fa fa-arrow-circle-right"></i>
      Join Server
    </a>
  </div>
</div>