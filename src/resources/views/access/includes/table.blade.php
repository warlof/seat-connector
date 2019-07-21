<div class="nav-tabs-custom">
  @include('seat-connector::access.includes.navigation')

  <div class="tab-content">
    <div class="tab-pane active">
      {!! $dataTable->table(['class' => 'table table-condensed table-hover table-responsive no-margin']) !!}
    </div>
  </div>
</div>