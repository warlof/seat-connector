<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{ trans('seat-connector::seat.access') }}</h3>
  </div>
  <div class="panel-body">
    @include('seat-connector::access.includes.navigation')

    <div class="box-body">
      {!! $dataTable->table(['class' => 'table table-condensed table-hover table-responsive no-margin']) !!}
    </div>
  </div>
</div>