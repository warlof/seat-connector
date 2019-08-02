@extends('web::layouts.grids.3-9')

@section('title', trans_choice('web::seat.log', 0))
@section('page_header', trans_choice('web::seat.log', 0))

@section('left')
  <div class="box box-solid">
    <div class="box-header with-border">
      <i class="fa fa-question-circle"></i>
      <h3 class="box-title">{{ trans('seat-connector::seat.help') }}</h3>
    </div>
    <div class="box-body">
      <p class="text-justify">
        This section is designed to show you important information regarding your registered drivers and the connector health.
        You can use elements from this box in order to filter entries from the table.
      </p>

      <h4>Drivers</h4>
      <div class="form-group">
        <select id="connector-driver" class="form-control">
          <option value="">All</option>
          @foreach(config('seat-connector.drivers') as $driver => $metadata)
            <option value="{{ $driver }}">{{ ucfirst($metadata['name']) }}</option>
          @endforeach
        </select>
      </div>

      <h4>Levels <button type="button" class="close" id="connector-log-reset">&times;</button></h4>
      <table class="table text-center no-border" id="connector-log-level" data-level="">
        <tbody>
          <tr>
            <td>
              <button type="button" class="btn btn-xs btn-block btn-default" data-level="debug">Debug</button>
            </td>
            <td>
              <button type="button" class="btn btn-xs btn-block btn-info" data-level="info">Info</button>
            </td>
            <td>
              <button type="button" class="btn btn-xs btn-block btn-success" data-level="notice">Notice</button>
            </td>
            <td>
              <button type="button" class="btn btn-xs btn-block btn-warning" data-level="warning">Warning</button>
            </td>
          </tr>
          <tr>
            <td>
              <button type="button" class="btn btn-xs btn-block btn-danger" data-level="error">Error</button>
            </td>
            <td>
              <button type="button" class="btn btn-xs btn-block btn-danger" data-level="critical"><i class="fa fa-free-code-camp"></i> Critical</button>
            </td>
            <td>
              <button type="button" class="btn btn-xs btn-block btn-warning" data-level="alert"><i class="fa fa-warning"></i> Alert</button>
            </td>
            <td>
              <button type="button" class="btn btn-xs btn-block btn-danger" data-level="emergency"><i class="fa fa-life-ring"></i> Emergency</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
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

  <script>
    $('#connector-driver').change(function(){
      window.LaravelDataTables["dataTableBuilder"].ajax.reload();
    }).select2();

    $('#connector-log-level').find('button').on('click', function () {
      $('#connector-log-level').data('level', $(this).data('level'));
      window.LaravelDataTables["dataTableBuilder"].ajax.reload();
    });

    $('#connector-log-reset').on('click', function () {
      $('#connector-log-level').data('level', '');
      window.LaravelDataTables["dataTableBuilder"].ajax.reload();
    })
  </script>
@endpush
