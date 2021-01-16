@extends('web::layouts.grids.3-9')

@section('title', trans_choice('web::seat.log', 0))
@section('page_header', trans_choice('web::seat.log', 0))

@section('left')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        <i class="fas fa-question-circle"></i> {{ trans('seat-connector::seat.help') }}
      </h3>
    </div>
    <div class="card-body">
      <p class="text-justify">
        This section is designed to show you important information regarding your registered drivers and the connector health.
        You can use elements from this box in order to filter entries from the table.
      </p>

      <h4>Drivers</h4>
      <div class="form-group">
        <select id="connector-driver" class="form-control">
          <option value="">All</option>
          @foreach(config('seat-connector.drivers', []) as $driver => $metadata)
            <option value="{{ $driver }}">{{ ucfirst($metadata['name']) }}</option>
          @endforeach
        </select>
      </div>

      <h4>Levels <button type="button" class="close" id="connector-log-reset">&times;</button></h4>
      <div id="connector-log-level" data-level="">
        <div class="row">
          <div class="col">
            <button type="button" class="btn btn-xs btn-block btn-default" data-level="debug">
              <i class="fas fa-bug"></i> Debug
            </button>
          </div>
          <div class="col">
            <button type="button" class="btn btn-xs btn-block btn-info" data-level="info">
              <i class="fas fa-info"></i> Info
            </button>
          </div>
          <div class="col">
            <button type="button" class="btn btn-xs btn-block btn-success" data-level="notice">
              <i class="fas fa-question-circle"></i> Notice
            </button>
          </div>
          <div class="col">
            <button type="button" class="btn btn-xs btn-block btn-warning" data-level="warning">
              <i class="fas fa-exclamation"></i> Warning
            </button>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col">
            <button type="button" class="btn btn-xs btn-block btn-danger" data-level="error">
              <i class="fas fa-exclamation-circle"></i> Error
            </button>
          </div>
          <div class="col">
            <button type="button" class="btn btn-xs btn-block bg-maroon" data-level="critical">
              <i class="fab fa-free-code-camp"></i> Critical
            </button>
          </div>
          <div class="col">
            <button type="button" class="btn btn-xs btn-block bg-orange" data-level="alert">
              <i class="fas fa-exclamation-triangle"></i> Alert
            </button>
          </div>
          <div class="col">
            <button type="button" class="btn btn-xs btn-block bg-pink" data-level="emergency">
              <i class="fas fa-life-ring"></i> Emerg.
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop

@section('right')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        <i class="fas fa-list"></i> {{ trans('seat-connector::seat.journal') }}
      </h3>
      <div class="card-tools">
        <div class="input-group input-group-sm">
          <form method="post" action="{{ route('seat-connector.logs.destroy') }}">
            {!! csrf_field() !!}
            {!! method_field('DELETE') !!}
            <button class="btn btn-sm btn-danger" type="submit"><i class="fas fa-trash-alt"></i> Clear</button>
          </form>
        </div>
      </div>
    </div>
    <div class="card-body">
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
