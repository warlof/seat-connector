<ul class="nav nav-tabs pull-right" id="connector-table-filters">
  <li role="presentation" data-filter="alliance">
    <a href="#">{{ trans('seat-connector::seat.alliance_filter') }}</a>
  </li>
  <li role="presentation" data-filter="corporation">
    <a href="#">{{ trans('seat-connector::seat.corporation_filter') }}</a>
  </li>
  <li role="presentation" data-filter="role">
  <a href="#">{{ trans('seat-connector::seat.role_filter') }}</a>
  </li>
  <li role="presentation" class="active" data-filter="user">
    <a href="#">{{ trans('seat-connector::seat.user_filter') }}</a>
  </li>
  <li class="pull-left header">
    <i class="fa fa-shield"></i>
    {{ trans('seat-connector::seat.access') }}
  </li>
</ul>