<form method="post" action="{{ route('seat-connector.acl.remove', [
  'permission_group_id' => $row->id,
  'entity_type'         => $row->entity_type,
  'entity_id'           => $row->entity_id,
]) }}">
  {{ csrf_field() }}
  {{ method_field('DELETE') }}
  <button class="btn btn-xs btn-danger pull-right">{{ trans('seat-connector::seat.remove') }}</button>
</form>