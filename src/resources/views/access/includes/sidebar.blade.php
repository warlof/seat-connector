<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{ trans('seat-connector::seat.toolbox') }}</h3>
  </div>
  <div class="panel-body">
    <form role="form" action="#" method="post">
      {{ csrf_field() }}

      <div class="box-body">

        <div class="form-group">
          <label for="connector-driver">{{ trans('seat-connector::driver') }}</label>
          <select name="connector-driver" id="connector-driver" class="form-control">
            @foreach(config('seat-connector.drivers') as $driver => $metadata)
              <option value="{{ $driver }}">{{ ucfirst($metadata['name']) }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="connector-filter-type">{{ trans_choice('web::seat.type', 1) }}</label>
          <select name="connector-filter-type" id="connector-filter-type" class="form-control">
            <option value="public">{{ trans('seat-connector::seat.public_filter') }}</option>
            <option value="group">{{ trans('seat-connector::seat.user_filter') }}</option>
            <option value="role">{{ trans('seat-connector::seat.role_filter') }}</option>
            <option value="corporation">{{ trans('seat-connector::seat.corporation_filter') }}</option>
            <option value="title">{{ trans('seat-connector::seat.title_filter') }}</option>
            <option value="alliance">{{ trans('seat-connector::seat.alliance_filter') }}</option>
          </select>
        </div>

        <div class="form-group">
          <label for="connector-filter-group">{{ trans('web::seat.username') }}</label>
          <select name="connector-filter-groupd" id="connector-filter-group" class="form-control" disabled></select>
        </div>

        <div class="form-group">
          <label for="connector-filter-role">{{ trans_choice('web::seat.role', 1) }}</label>
          <select name="connector-filter-role" id="connector-filter-role" class="form-control" disabled></select>
        </div>

        <div class="form-group">
          <label for="connector-filter-corporation">{{ trans_choice('web::seat.corporation', 1) }}</label>
          <select name="connector-filter-corporation" id="connector-filter-corporation" class="form-control" disabled></select>
        </div>

        <div class="form-group">
          <label for="connector-filter-title">{{ trans_choice('web::seat.title', 1) }}</label>
          <select name="connector-filter-title" id="connector-filter-title" class="form-control" disabled></select>
        </div>

        <div class="form-group">
          <label for="connector-filter-alliance">{{ trans('web::seat.alliance') }}</label>
          <select name="connector-filter-alliance" id="connector-filter-alliance" class="form-control" disabled></select>
        </div>

        <div class="form-group">
          <label for="connector-permission-group">{{ trans('seat-connector::seat.permission_group') }}</label>
          <select name="connector-permission-group" id="connector-permission-group" class="form-control"></select>
        </div>

      </div>

      <div class="box-footer">
        <button type="submit" class="btn btn-primary pull-right">{{ trans('web::seat.add') }}</button>
      </div>

    </form>
  </div>
</div>