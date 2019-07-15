@extends('web::layouts.grids.12')

@section('title', trans_choice('seat-connector::seat.user-mapping', 0))
@section('page_header', trans_choice('seat-connector::seat.user-mapping', 0))

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
        @if($loop->first)
          <li class="active">
            <a href="#tab_{{ $loop->index }}" role="tab" data-toggle="tab"
               onclick="driverDataTable('tab_{{ $loop->index }}', '{{ $metadata['name'] }}');">
              {{ ucfirst($metadata['name']) }}
            </a>
          </li>
        @else
          <li>
            <a href="#tab_{{ $loop->index }}" role="tab" data-toggle="tab"
               onclick="driverDataTable('tab_{{ $loop->index }}', '{{ $metadata['name'] }}');">
              {{ ucfirst($metadata['name']) }}
            </a>
          </li>
        @endif
      @endforeach
    </ul>
    <div class="tab-content">
      @foreach(config('seat-connector.drivers',[]) as $metadata)
        @if($loop->first)
          @include('seat-connector::users.partials.tabs', ['tab_id' => 'tab_' . $loop->index, 'tab_class' => 'tab-pane active'])
        @else
          @include('seat-connector::users.partials.tabs', ['tab_id' => 'tab_' . $loop->index, 'tab_class' => 'tab-pane'])
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
                ajax: '{{ route('seat-connector.users') }}',
                params: {
                    'driver': driver
                },
                columns: [
                    {data: 'group_id', type: 'num'},
                    {data: 'user_id', type: 'num'},
                    {data: 'user_name', type: 'string'},
                    {data: 'discord_id', type: 'num'},
                    {data: 'nick', type: 'string'}
                ]
            });
        }
    }
  </script>
@endpush