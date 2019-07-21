<form method="post" action="{{ route('seat-connector.acl.remove') }}">
  {{ csrf_field() }}
  {{ method_field('DELETE') }}
  <input type="hidden" name="permission_group_id" value="{{ $row->id }}" />
  <input type="hidden" name="entity_type" value="{{ $row->entity_type }}" />
  <input type="hidden" name="entity_id" value="{{ $row->entity_id }}" />
  <button type="submit" class="btn btn-xs btn-danger pull-right">{{ trans('seat-connector::seat.remove') }}</button>
</form>