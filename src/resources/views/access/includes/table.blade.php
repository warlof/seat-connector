<div class="card">
  <div class="card-header d-flex p-0">
    <h3 class="card-title p-3">
      <i class="fas fa-shield-alt"></i> {{ trans('seat-connector::seat.access') }}
    </h3>
    @include('seat-connector::access.includes.navigation')
  </div>

  <div class="card-body">
    <div class="tab-content">
      <div class="tab-pane active">
        {!! $dataTable->table(['class' => 'table table-striped table-hover']) !!}
      </div>
    </div>
  </div>
</div>