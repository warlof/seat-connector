<div class="col-md-3">
  <div class="small-box bg-gray">
    <div class="inner">
      <h4 class="text-uppercase">
        <strong>{{ $metadata['name'] }}</strong>
      </h4>
      <div class="row">
        <div class="col-4 text-right">
          <b>Name</b>
        </div>
        <div class="col-8">
          @if($identity = $identities->where('connector_type', $driver)->first())
            <i>{{ $identity->connector_name }}</i>
          @endif
        </div>
      </div>
      <div class="row">
        <div class="col-4 text-right">
          <b>Unique ID</b>
        </div>
        <div class="col-8">
          @if($identity = $identities->where('connector_type', $driver)->first())
            <i>{{ $identity->unique_id }}</i>
          @endif
        </div>
      </div>
      <div class="row">
        <div class="col-4 text-right">
          <b>Created on</b>
        </div>
        <div class="col-8">
          @if($identity = $identities->where('connector_type', $driver)->first())
            <i>{{ $identity->created_at }}</i>
          @endif
        </div>
      </div>
      <div class="row">
        <div class="col-4 text-right">
          <b>Status</b>
        </div>
        <div class="col-8">
          @if($identities->where('connector_type', $driver)->isNotEmpty())
            <span class="badge badge-success p-1">
              <i class="fas fa-check-circle mr-1"></i> Registered
            </span>
          @else
            <span class="badge badge-danger p-1">
              <i class="fas fa-times-circle mr-1"></i> Unregistered
            </span>
          @endif
        </div>
      </div>
    </div>
    @if(array_key_exists('icon', $metadata))
      <div class="icon">
        <i class="{{ $metadata['icon'] }}"></i>
      </div>
    @endif
    <a class="small-box-footer" href="{{ route(sprintf('seat-connector.drivers.%s.registration', $driver)) }}" target="_blank">
      <i class="fa fa-arrow-circle-right"></i>
      Join Server
    </a>
  </div>
</div>