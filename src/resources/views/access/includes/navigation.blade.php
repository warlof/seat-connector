<ul class="nav nav-tabs pull-right" id="connector-table-filters">
  <li role="presentation" data-filter="alliances">
    <a href="#">{{ trans('seat-connector::seat.alliance_filter') }}</a>
  </li>
  <li role="presentation" data-filter="titles">
    <a href="#">{{ trans('seat-connector::seat.title_filter') }}</a>
  </li>
  <li role="presentation" data-filter="corporations">
    <a href="#">{{ trans('seat-connector::seat.corporation_filter') }}</a>
  </li>
  <li role="presentation" data-filter="roles">
  <a href="#">{{ trans('seat-connector::seat.role_filter') }}</a>
  </li>
  <li role="presentation" data-filter="groups">
    <a href="#">{{ trans('seat-connector::seat.user_filter') }}</a>
  </li>
  <li role="presentation" data-filter="public">
    <a href="#">{{ trans('seat-connector::seat.public_filter') }}</a>
  </li>
  <li role="presentation" data-filter="" class="active">
    <a href="#">{{ trans('seat-connector::seat.all_filter') }}</a>
  </li>
  <li class="pull-left header">
    <i class="fa fa-shield"></i>
    {{ trans('seat-connector::seat.access') }}
  </li>
</ul>