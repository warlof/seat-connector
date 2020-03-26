<div class="card">
  <div class="card-header">
    <h3 class="card-title">
      <i class="fas fa-plus"></i> {{ trans('seat-connector::seat.toolbox') }}
    </h3>
  </div>
  <div class="card-body">
    <form role="form" method="post" id="connector-toolbox">
      {{ csrf_field() }}

        <div class="form-group">
          <label for="connector-driver">{{ trans('seat-connector::seat.driver') }}</label>
          <select name="connector-driver" id="connector-driver" class="form-control">
            @foreach(config('seat-connector.drivers', []) as $driver => $metadata)
              <option value="{{ $driver }}">{{ ucfirst($metadata['name']) }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="connector-filter-type">{{ trans_choice('web::seat.type', 1) }}</label>
          <select name="entity_type" id="connector-filter-type" class="form-control">
            <option value="public">{{ trans('seat-connector::seat.public_filter') }}</option>
            <option value="groups">{{ trans('seat-connector::seat.user_filter') }}</option>
            <option value="roles">{{ trans('seat-connector::seat.role_filter') }}</option>
            <option value="corporations">{{ trans('seat-connector::seat.corporation_filter') }}</option>
            <option value="titles">{{ trans('seat-connector::seat.title_filter') }}</option>
            <option value="alliances">{{ trans('seat-connector::seat.alliance_filter') }}</option>
          </select>
        </div>

        <div class="form-group">
          <label for="connector-filter-groups">{{ trans('web::seat.username') }}</label>
          <select name="entity_id" id="connector-filter-groups" class="form-control" disabled></select>
        </div>

        <div class="form-group">
          <label for="connector-filter-roles">{{ trans_choice('web::seat.role', 1) }}</label>
          <select name="entity_id" id="connector-filter-roles" class="form-control" disabled></select>
        </div>

        <div class="form-group">
          <label for="connector-filter-corporations">{{ trans_choice('web::seat.corporation', 1) }}</label>
          <select name="entity_id" id="connector-filter-corporations" class="form-control" disabled></select>
        </div>

        <div class="form-group">
          <label for="connector-filter-titles">{{ trans_choice('web::seat.title', 1) }}</label>
          <select name="entity_id" id="connector-filter-titles" class="form-control" disabled></select>
        </div>

        <div class="form-group">
          <label for="connector-filter-alliances">{{ trans('web::seat.alliance') }}</label>
          <select name="entity_id" id="connector-filter-alliances" class="form-control" disabled></select>
        </div>

        <div class="form-group">
          <label for="connector-set">{{ trans_choice('seat-connector::seat.sets', 1) }}</label>
          <select name="set_id" id="connector-set" class="form-control"></select>
        </div>

    </form>
  </div>
  <div class="card-footer">
    <button type="submit" form="connector-toolbox" class="btn btn-success float-right">{{ trans('web::seat.add') }}</button>
  </div>
</div>