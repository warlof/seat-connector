@extends('web::layouts.grids.12')

@section('title', trans('seat-connector::seat.user_mapping'))
@section('page_header', trans('seat-connector::seat.user_mapping'))

@section('full')
  @if(config('seat-connector.drivers', []) == [])
    <div class="callout callout-warning">
      <h4>No driver available!</h4>
      <p>In order to use this page, you need to install a seat-connector driver.</p>
    </div>
  @else
  <div class="nav-tabs-custom" id="seat-connector-users-tabs">
    <ul class="nav nav-tabs">
      @foreach(config('seat-connector.drivers', []) as $metadata)
        @if($loop->last)
          <li class="active">
            <a href="#tab_{{ $metadata['name'] }}" role="tab" data-toggle="tab"
               onclick="driverDataTable('tab_{{ $metadata['name'] }}', '{{ $metadata['name'] }}');">
              {{ ucfirst($metadata['name']) }}
            </a>
          </li>
        @else
          <li>
            <a href="#tab_{{ $metadata['name'] }}" role="tab" data-toggle="tab"
               onclick="driverDataTable('tab_{{ $metadata['name'] }}', '{{ $metadata['name'] }}');">
              {{ ucfirst($metadata['name']) }}
            </a>
          </li>
        @endif
      @endforeach
    </ul>
    <div class="tab-content">
      @foreach(config('seat-connector.drivers',[]) as $metadata)
        @if($loop->last)
          @include('seat-connector::users.partials.tabs', ['id' => 'tab_' . $metadata['name'], 'class' => 'tab-pane active', 'metadata' => $metadata])
        @else
          @include('seat-connector::users.partials.tabs', ['id' => 'tab_' . $metadata['name'], 'class' => 'tab-pane', 'metadata' => $metadata])
        @endif
      @endforeach
    </div>
  </div>
  @endif
@stop

@push('javascript')
  {!! $dataTable->scripts() !!}

  <script>
    // call the driver route and update displayed table
    function driverDataTable(tab_id, driver) {
        if (! $.fn.dataTable.isDataTable('#' + tab_id + '_table')) {
            $('#' + tab_id + '_table').DataTable({
                dom: 'Bfrtip',
                processing: true,
                serverSide: true,
                order: [[0, 'desc']],
                ajax: {
                    url: '{{ route('seat-connector.users') }}',
                    data: {
                        driver: driver
                    }
                },
                columns: [
                    {data: 'group_id', name: 'group_id', type: 'num'},
                    {data: 'character_id', name: 'character_id', type: 'num'},
                    {data: 'name', name: 'name', type: 'string'},
                    {data: 'connector_id', name: 'connector_id', type: 'string'},
                    {data: 'connector_name', name: 'connector_name', type: 'string'}
                ]
            });
        }
    }
  </script>
@endpush